@extends('layouts.masterLayout')

@section('html_title', 'Read Mail')

@section('page_content')

  <!-- override the CCP styles -->
  <style>
    #mail {
      font-size: 1.0em;
      line-height: 100%;
    }
    #mail font {
      font-size: inherit;
      color: #000000 !important;
    }
  </style>

  {{-- open a empty form to get a crsf token --}}
  {{ Form::open(array()) }} {{ Form::close() }}

  <div class="row">
    <div class="col-md-9">

      <div class="box">
        <div class="box-header">
          <h4 class="box-title">
            <i class="fa fa-envelope-o"></i> {{ $message->title }}
            <small>

              <b>From:</b>
              <a href="{{ action('CharacterController@getView', array('characterID' => $message->senderID)) }}">
                  {{ Seat\services\helpers\Img::character($message->senderID, 16, array('class' => 'img-circle eveIcon small')) }}
              </a>
              {{ $message->senderName }} sent about {{ Carbon\Carbon::parse($message->sentDate)->diffForHumans() }}
              @ {{ $message->sentDate }}

              |<!-- determine the recipient information to display -->

              {{-- corporations --}}
              @if (strlen($message->toCorpOrAllianceID) > 0 && count(explode(',', $message->toCorpOrAllianceID)) > 0)

                <b>To Corp/Alliance:</b>

                  @foreach (explode(',', $message->toCorpOrAllianceID) as $corp_alliance)
                        {{ Seat\services\helpers\Img::html($corp_alliance, 16, array('class' => 'img-circle eveIcon small')) }}
                        <span rel="id-to-name">{{ $corp_alliance }}</span>
                  @endforeach

              @endif

              {{-- characters --}}
              @if (strlen($message->toCharacterIDs) > 0 && count(explode(',', $message->toCharacterIDs)) > 0)

                <b>To Characters:</b>

                  @foreach (explode(',', $message->toCharacterIDs) as $characterID)
                    <a href="{{ action('CharacterController@getView', array('characterID' => $characterID)) }}">
                        {{ Seat\services\helpers\Img::character($characterID, 16, array('class' => 'img-circle eveIcon small')) }}
                    </a>
                    <span rel="id-to-name">{{ $characterID }}</span>
                  @endforeach

              @endif

              {{-- mailing lists --}}
              @if (strlen($message->toListID) > 0 && count(explode(',', $message->toListID)) > 0)

                <b>To Mailing List:</b>

                  @foreach (explode(',', $message->toListID) as $list)

                    @if(array_key_exists($list, $mailing_list_names))
                      {{ $mailing_list_names[$list] }}
                    @else
                      Unknown Mailing List {{ $list }}
                    @endif

                  @endforeach

              @endif

          </small>
        </h4>
      </div>

      <div class="box-body">
        <div id="mail">
          {{ $message->body }}
        </div>
      </div><!-- /.box-body -->

      <div class="box-footer clearfix">
        <div class="pull-right">
          Recipient Type(s):
            @if (strlen($message->toCorpOrAllianceID) > 0)
              <b>{{ count(explode(',', $message->toCorpOrAllianceID)) }}</b> Corporation(s) / Alliance(s)
            @endif
            @if (strlen($message->toCharacterIDs) > 0)
              <b>{{ count(explode(',', $message->toCharacterIDs)) }}</b> Character(s)
            @endif
            @if (strlen($message->toListID) > 0)
              <b>{{ count(explode(',', $message->toListID)) }}</b> Mailing List(s)
            @endif
          </div>
        </div>
      </div>

      </div>
      <div class="col-md-3">
        <div class="box box-solid box-solid">
          <div class="box-header">
            <h3 class="box-title">Recipients</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body no-padding">
            <table class="table table-condensed">
              <tbody>
                <tr>
                  <th>Character Name</th>
                </tr>

                  @foreach ($recipients as $recipient)

                    <tr>
                      <td>
                        <a href="{{ action('CharacterController@getView', array('characterID' => $recipient)) }}">
                            {{ Seat\services\helpers\Img::character($recipient, 16, array('class' => 'img-circle eveIcon small')) }}
                            <span rel="id-to-name">{{ $recipient }}</span>
                        </a>
                      </td>
                    </tr>

                  @endforeach

              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div>
      </div>
    </div>

@stop

@section('javascript')

  <script>

    $(document).ready(function() {

      var items = [];
      var arrays = [], size = 250;

      $('[rel="id-to-name"]').each( function(){
         //add item to array
         items.push( $(this).text() );
      });

      var items = $.unique( items );

      while (items.length > 0)
          arrays.push(items.splice(0, size));

      $.each(arrays, function( index, value ) {

        $.ajax({
          type: 'POST',
          url: "{{ action('HelperController@postResolveNames') }}",
          data: {
            'ids': value.join(',')
          },
          success: function(result){
            $.each(result, function(id, name) {

              $("span:contains('" + id + "')").html(name);
            })
          },
          error: function(xhr, textStatus, errorThrown){
           console.log(xhr);
           console.log(textStatus);
           console.log(errorThrown);
         }
       });
      });
    });
  </script>

@stop
