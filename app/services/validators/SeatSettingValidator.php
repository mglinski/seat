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

namespace App\Services\Validators;

class SettingValidator extends Validator
{

    public static $rules = array(
        'app_name' => 'required',
        'registration_enabled' => 'required',
        'required_mask' => 'required|numeric|min:176693568',
        'administrative_contact' => 'required|email',
        'color_scheme' => 'required|in:blue,black',
        'thousand_seperator' => 'in:" ",",","."|size:1',
        'decimal_seperator' => 'required|in:",","."|size:1',
        'seatscheduled_character' => 'required|in:true,false',
        'seatscheduled_corporation' => 'required|in:true,false',
        'seatscheduled_corporation_assets' => 'required|in:true,false',
        'seatscheduled_corporation_wallets' => 'required|in:true,false',
        'seatscheduled_eve' => 'required|in:true,false',
        'seatscheduled_map' => 'required|in:true,false',
        'seatscheduled_server' => 'required|in:true,false',
        'seatscheduled_notifications' => 'required|in:true,false',
        'seatscheduled_queue_cleanup' => 'required|in:true,false'
    );
}
