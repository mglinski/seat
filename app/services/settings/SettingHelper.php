<?php
/*
The MIT License (MIT)

Copyright (c) 2014 eve-seat

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

namespace App\Services\Settings;

class SettingHelper
{

    /*
    |--------------------------------------------------------------------------
    | Default SeAT Settings
    |--------------------------------------------------------------------------
    |
    | The following properties define the default settings for SeAT. As the
    | application becomes aware of more settings, they can simply be
    | added here without the need to run a database Seed.
    |
    */

    static $seat_defaults = array(

        // Application Globals
        'app_name' => 'SeAT',
        'required_mask' => 176693568,
        'registration_enabled' => true,
        'administrative_contact' => null,

        // User personalization
        'color_scheme' => 'blue',
        'thousand_seperator' => ' ',
        'decimal_seperator' => '.',
        'main_character_id' => 1,
        'main_character_name' => null,
        'email_notifications' => false,

        // SeAT Backend
        'seatscheduled_character' => true,
        'seatscheduled_corporation' => true,
        'seatscheduled_corporation_assets' => true,
        'seatscheduled_corporation_wallets' => true,
        'seatscheduled_eve' => true,
        'seatscheduled_map' => true,
        'seatscheduled_server' => true,
        'seatscheduled_notifications' => true,
        'seatscheduled_queue_cleanup' => true

    );

    /*
    |--------------------------------------------------------------------------
    | User Controlled SeAT Settings
    |--------------------------------------------------------------------------
    |
    | Some settings may be user controlled and should cascade accordignly.
    | This array determines which settings are "safe" for users to set.
    |
    */

    static $user_settings = array(
        'color_scheme',
        'thousand_seperator',
        'decimal_seperator',
        'main_character_id',
        'main_character_name',
        'email_notifications'
    );

    /*
    |--------------------------------------------------------------------------
    | getSetting()
    |--------------------------------------------------------------------------
    |
    | Attempts to retreive the value of a SeAT setting.
    |
    | By default, we will first try to get the user defined value of settings
    | that are considered ok for users to get, thereafter we will lookup
    | the setting in the database which will have the store to override
    | the SeAT defaults, and finally, we will fallback to the defaults
    | as defined in this Class
    |
    */

    public static function getSetting($setting_name, $system_setting = false, $user_id = null) {

        // In some cases, we may want to retrieve the system
        // configured/default setting regardless of the
        // logged in state of the user. For this we
        // will check $system_setting.
        if(!$system_setting) {

            // As per $this::$user_settings, some settings are
            // considered OK for users to set. If we have a
            // logged in user and have a $setting_name
            // that is in $user_settings we will
            // lookup the setting for the user
            // first
            if(\Auth::check() || !is_null($user_id)) {

                // When specifying a $user_id, we typically want to
                // get a specific user's settings. Eg: This is the
                // case when notications wants to check if the
                // user is configured to retreive email
                // alerts.
                $effective_user_id = \Auth::check() ? \Auth::User()->id : $user_id;

                // Check that the setting is one that is OK
                // for user to have set.
                if (in_array($setting_name, SettingHelper::$user_settings)) {

                    // If the database is not ready for us to attempt
                    // setting retrieval, then the following call
                    // will throw an exception. This can happen
                    // with new installs when the application
                    // bootstraps and wants things like the
                    // app_name etc. So, to combat this,
                    // we catch it and allow the flow
                    // to follow down to the
                    // defaults
                    try {

                        // Looks like we have a user setting, lets do a
                        // db lookup for it.
	                    $cache_key = self::getUserSettingCacheKeyPrefix($effective_user_id, $setting_name);
	                    $setting_value = \Cache::get($cache_key, function() use ($effective_user_id, $setting_name, $cache_key) {

		                    $setting_value = \SeatUserSetting::where('user_id', $effective_user_id)
		                        ->where('setting', $setting_name)
			                    ->pluck('value');
		                    \Cache::forever($cache_key, $setting_value);

		                    return $setting_value;
	                    });

                        if($setting_value) {
	                        // Found the user setting, return that!
	                        return $setting_value;
                        }


                    } catch (\Exception $e) {}
                }
            }
        }

        // If the database is not ready for us to attempt
        // setting retrieval, then the following call
        // will throw an exception. This can happen
        // with new installs when the application
        // bootstraps and wants things like the
        // app_name etc. So, to combat this,
        // we catch it and allow the flow
        // to follow down to the
        // defaults
        try {

            // So we dont have a user setting for whatever reason,
            // so lets check the SeAT global settings.
	        $cache_key = self::getSystemSettingCacheKeyPrefix($setting_name);
	        $setting_value = \Cache::get($cache_key, function() use ($setting_name, $cache_key) {

		        $setting_value = \SeatSetting::where('setting', $setting_name)->pluck('value');
		        \Cache::forever($cache_key, $setting_value);

		        return $setting_value;
	        });

            // If we have a database entry for the setting, return
            // that as the value
            if($setting_value) {
	            // Found the system setting, return that!
	            return $setting_value;
            }

        } catch (\Exception $e) {}

        // Finally, and as a last resort, check if we have a
        // default value for this setting defined. If not
        // we will throw a exception about it
        if(!array_key_exists($setting_name, SettingHelper::$seat_defaults))
            throw new \Exception('Unable to find setting ' . $setting_name);

        // Return the SeAT default then
        return SettingHelper::$seat_defaults[$setting_name];
    }

   /*
    |--------------------------------------------------------------------------
    | setSetting()
    |--------------------------------------------------------------------------
    |
    | Attempts to set the name and value a SeAT setting
    |
    | By default, we will first try to get the user defined value of settings
    | that are considered ok for users to get, thereafter we will lookup
    | the setting in the database which will have the store to override
    | the SeAT defaults, and finally, we will fallback to the defaults
    | as defined in this Class
    |
    */

    public static function setSetting($setting_name, $setting_value, $user_id = null) {

        // We are going to take the stance that all settings
        // should have a default value configured in the
        // SettingsHelper::$seat_default array(). If
        // this is not the case, throw an
        // exception.
        if (!array_key_exists($setting_name, SettingHelper::$seat_defaults))
            throw new \Exception('Attempting to set a foreign setting ' . $setting_name);

        // If $user_id is not null, then we can assume the
        // attempt is being made to save a user setting.
        // To try and protect ourselves a little, we
        // also check that the setting_name is in
        // the array of settings that are to be
        // considered safe for users to set
        if(!is_null($user_id)) {

            if (!in_array($setting_name, SettingHelper::$user_settings))
                throw new \Exception('Attempting to set a illegal setting ' . $setting_name);

            // Everything seems OK, lets find the setting if
            // it already exists, and update its value
            $user_setting = \SeatUserSetting::firstOrNew(array('setting' => $setting_name, 'user_id' => \Auth::User()->id));

            $user_setting->user_id = $user_id;
            $user_setting->setting = $setting_name;
            $user_setting->value = $setting_value;
            $user_setting->save();

	        // cache this value forever to save on DB calls
	        $cache_key = self::getUserSettingCacheKeyPrefix(\Auth::User()->id, $setting_name);
	        \Cache::forever($cache_key, $setting_value);

            // Return as we are done
            return true;

        } else {

            // Assuming $user_id was null, we can assume the
            // value should be set globally. So lets get
            // right to it.
            $global_setting = \SeatSetting::firstOrNew(array('setting' => $setting_name));

            $global_setting->setting = $setting_name;
            $global_setting->value = $setting_value;
            $global_setting->save();

	        // cache this value forever to save on DB calls
	        $cache_key = self::getSystemSettingCacheKeyPrefix($setting_name);
	        \Cache::forever($cache_key, $setting_value);

            return true;
        }

        // If we were not able to do anything useful, return
        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | getAllSettings()
    |--------------------------------------------------------------------------
    |
    | Attempts to retrieve the value of all SeAT settings.
    |
    */

    public static function getAllSettings() {

        $calculated_settings = array();
        // Loop over all of the settings we know of
        // and populate the return array with the
        // calculated values
        foreach (SettingHelper::$seat_defaults as $setting_name => $setting_value)
            $calculated_settings[$setting_name] = SettingHelper::getSetting($setting_name);

        // Once we have everything set, return
        return $calculated_settings;

    }

    /*
    |--------------------------------------------------------------------------
    | getUserSettingCacheKeyPrefix()
    |--------------------------------------------------------------------------
    |
    | Get cache prefix for user settings
    |
    */

    public static function getUserSettingCacheKeyPrefix($user_id, $setting_name) {
        return 'seat.settings.user.'.(int)$user_id.'.'.$setting_name;
    }

    /*
    |--------------------------------------------------------------------------
    | getSystemSettingCacheKeyPrefix()
    |--------------------------------------------------------------------------
    |
    | Get cache prefix for user settings
    |
    */

    public static function getSystemSettingCacheKeyPrefix($setting_name) {
        return 'seat.settings.system.'.$setting_name;
    }

}
