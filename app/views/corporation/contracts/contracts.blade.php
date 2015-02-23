@extends('layouts.masterLayout')

@section('html_title', 'Corporation Contracts')

@section('page_content')

  {{-- open a empty form to get a crsf token --}}
  {{ Form::open(array()) }} {{ Form::close() }}

  <div class="row">
    <div class="col-md-12">

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Contract List ({{ count($contracts_courier) +  count($contracts_other)}})</h3>
        </div><!-- /.box-header -->

        <div class="box-body no-padding">
          <div class="row">

            {{-- Building box for contracts like Itemexchange and auction --}}
            <div class="col-md-6">
              <div class="box box-solid box-primary">
                <div class="box-header">
                  <h3 class="box-title">Item Exchange &amp; Auction ({{ count($contracts_other) }})</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-minus" id="collapse-box"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <div class="row">
                    <div class="col-md-12">
                      <table class="table table-hover table-condensed">
                        <tbody>
                          <tr>
                            <th style="width: 200px">Issuer</th>
                            <th style="width: 200px">Assignee</th>
                            <th>type</th>
                            <th style="width: 30px">Status</th>
                            <th style="width: 30px"></th>
                          </tr>
                        </tbody>
                      </table>

                        {{-- Loop over other contracts and display --}}
                        @foreach ($contracts_other as $contract)

                          <table class="table table-hover table-condensed">
                            <tbody style="border-top:0px solid #FFF">
                              <tr class="item-container">
                                <td style="width: 200px">
                                    {{ Seat\services\helpers\Img::html($contract['issuerID'], 16, array('class' => 'img-circle eveIcon small')) }}
                                  <span rel="id-to-name">{{ $contract['issuerID'] }}</span>
                                </td>
                                <td style="width: 200px">
                                  @if ($contract['assigneeID'] <> 0)
                                        {{ Seat\services\helpers\Img::html($contract['assigneeID'], 16, array('class' => 'img-circle eveIcon small')) }}
                                    <span rel="id-to-name">{{ $contract['assigneeID'] }}</span>
                                  @else
                                    Unknown Assignee
                                  @endif
                                </td>
                                <td>{{ $contract['type'] }}</td>
                                {{-- Check the status and display icon for this status --}}
                                <td style="width: 30px">
                                  @if($contract['status'] == 'Completed')
                                    <span class="text-green" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-check"></i></span>
                                  @elseif($contract['status'] == 'Outstanding')
                                    <span class="text-orange" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-clock-o"></i></span>
                                  @else
                                    <span class="text-red" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-times"></i></span>
                                  @endif
                                </td>
                                <td style="text-align: right; width: 30px"><i class="fa fa-plus viewcontent contracts" style="cursor: pointer;"></i></td>
                              </tr>
                            </tbody>
                          </table>

                          {{-- Add additionnal information for the contracts and give a specific class (tbodycontent) for toggle it --}}
                          <div class="col-md-12 tbodycontent">
                            <ul class="list-unstyled">
                              <li>
                                <i class="fa fa-map-marker"></i>
                                <span data-toggle="tooltip" title="" data-original-title="{{ $contract['startlocation'] }}">
                                  <b>{{ str_limit($contract['startlocation'], 100, $end = '...') }}</b>
                                </span>
                              </li>
                              @if(isset($contract['title']) && strlen($contract['title']) > 0)
                                <li>
                                  <i class="fa fa-bullhorn" data-original-title=" {{ $contract['title'] }}" title="" data-toggle="tooltip"></i>
                                  Title: <b>{{ str_limit($contract['title'], 100, $end = '...') }}</b>
                                </li>
                              @endif
                              <li>
                                <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateIssued'] }}" title="" data-toggle="tooltip"></i>
                                Issued: <b>{{ Carbon\Carbon::parse($contract['dateIssued'])->diffForHumans() }}</b>
                              </li>

                              {{-- If the contract is not completed, we display the expiration date else nothing --}}
                              @if(!isset($contract['dateCompleted']))
                                <li>
                                  <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateExpired'] }}" title="" data-toggle="tooltip"></i>
                                  Expires: <b>{{ Carbon\Carbon::parse($contract['dateExpired'])->diffForHumans() }}</b>
                                </li>
                              @endif

                              {{-- If the contract is completed we display the date --}}
                              @if(isset($contract['dateCompleted']))
                                <li>
                                  <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateCompleted'] }}" title="" data-toggle="tooltip"></i>
                                  Completed: <b>{{ Carbon\Carbon::parse($contract['dateCompleted'])->diffForHumans() }}</b>
                                  by
                                    {{ Seat\services\helpers\Img::html($contract['acceptorID'], 16, array('class' => 'img-circle eveIcon small')) }}
                                    <b><span rel="id-to-name">{{ $contract['acceptorID'] }}</span></b>
                                </li>
                              @endif
                              <li>
                                <i class="fa fa-money"></i>
                                Buyer will get <b><span class="text-green">{{ number_format($contract['reward'], 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</span></b> ISK
                              </li>
                              <li>
                                <i class="fa fa-money"></i>
                                Buyer will pay <b><span class="text-red">{{ number_format($contract['price'], 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</span></b> ISK
                              </li>

                              {{-- If the contract is an auction we display the buyout price --}}
                              @if($contract['type'] == 'Auction')
                                <li>
                                  <i class="fa fa-money"></i>
                                  <b>{{ number_format($contract['buyout'], 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</b> ISK buyout
                                </li>
                              @endif

                              {{-- Check if contract has contents --}}
                              @if(isset($contract['contents']) && count($contract['contents']) > 0)
                                <li><i class="fa fa-paperclip"></i> Contents</li>
                                <li>
                                  <div class="col-md-6">
                                    <ul>
                                      <li style="list-style:none;">
                                        <span class="text-green"><b>Buyer will get</b></span>
                                      </li>

                                      {{-- Loop over contents and display item in contract --}}
                                      @foreach($contract['contents'] as $content)

                                        <li style="list-style:none;">
                                          {{-- Check if it's a item request or not --}}
                                          @if($content['included'] == 1)
                                                {{ Seat\services\helpers\Img::type($content['typeID'], 16, array('class' => 'eveIcon small')) }}
                                                <span>{{ number_format($content['quantity'], 0, $settings['decimal_seperator'], $settings['thousand_seperator']) }} x {{ $content['typeName'] }}</span>
                                          @endif
                                        </li>

                                      @endforeach

                                    </ul>
                                  </div>
                                  <div class="col-md-6">
                                    <ul>
                                      <li style="list-style:none;">
                                        <span class="text-red"><b>Buyer will pay</b></span>
                                      </li>

                                      {{-- Loop over contents and display item requested in contract --}}
                                      @foreach($contract['contents'] as $content)

                                        <li style="list-style:none;">
                                          {{-- Check if it's a item request or not --}}
                                          @if($content['included'] == 0)
                                            {{ Seat\services\helpers\Img::type($content['typeID'], 16, array('class' => 'eveIcon small')) }}
                                            <span>{{ number_format($content['quantity'], 0, $settings['decimal_seperator'], $settings['thousand_seperator']) }} x {{ $content['typeName'] }}</span>
                                          @endif
                                        </li>

                                      @endforeach

                                    </ul>
                                  </div>
                                </li>
                              @endif
                            </ul>
                          </div><!-- ./col-md-12 -->

                        @endforeach

                    </div><!-- ./col-md-12 -->
                  </div><!-- ./row -->
                </div><!-- ./box-body -->
              </div><!-- ./box -->
            </div> <!-- ./col-md-6 -->

            {{-- Building box for courier contracts --}}
            <div class="col-md-6">
              <div class="box box-solid box-primary">
                <div class="box-header">
                  <h3 class="box-title">Courier ({{ count($contracts_courier) }})</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-minus" id="collapse-box"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <div class="row">
                    <div class="col-md-12">
                      <table class="table table-condensed">
                        <tbody>
                          <tr>
                            <th style="width: 200px">Issuer</th>
                            <th style="width: 200px">Assignee</th>
                            <th>type</th>
                            <th style="width: 30px">Status</th>
                            <th style="width: 30px"></th>
                          </tr>
                        </tbody>
                      </table>

                        {{-- Loop over contracts courier and display --}}
                        @foreach ($contracts_courier as $contract)

                          <table class="table table-hover table-condensed">
                            <tbody style="border-top:0px solid #FFF">
                              <tr class="item-container">
                                <td style="width: 200px">
                                    {{ Seat\services\helpers\Img::html($contract['issuerID'], 16, array('class' => 'img-circle eveIcon small')) }}
                                    <span rel="id-to-name">{{ $contract['issuerID'] }}</span>
                                </td>
                                <td style="width: 200px">
                                  @if ($contract['assigneeID'] <> 0)
                                        {{ Seat\services\helpers\Img::html($contract['assigneeID'], 16, array('class' => 'img-circle eveIcon small')) }}
                                        <span rel="id-to-name">{{ $contract['assigneeID'] }}</span>
                                  @else
                                    Unknown Assignee
                                  @endif
                                </td>
                                <td>{{ $contract['type'] }}</td>
                                <td style="width: 30px">
                                  @if($contract['status'] == 'Completed')
                                    <span class="text-green" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-check"></i></span>
                                  @elseif($contract['status'] == 'inProgress')
                                    <span class="text-blue" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-truck"></i></span>
                                  @elseif($contract['status'] == 'Outstanding')
                                    <span class="text-orange" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-clock-o"></i></span>
                                  @else
                                    <span class="text-red" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-times"></i></span>
                                  @endif
                                </td>
                                <td style="text-align: right; width: 30px"><i class="fa fa-plus viewcontent contracts" style="cursor: pointer;"></i></td>
                              </tr>
                            </tbody>
                          </table>

                          {{-- Add additionnal information for the contracts and give a specific class (tbodycontent) for toggle it --}}
                          <div class="col-md-12 tbodycontent">
                            <ul class="list-unstyled">
                              <li>
                                <i class="fa fa-flag-checkered"></i>
                                <span data-toggle="tooltip" title="" data-original-title="{{ $contract['startlocation'] }}">
                                  <b>{{ str_limit($contract['startlocation'], 50, $end = '...') }}</b>
                                </span> >>
                                <span data-toggle="tooltip" title="" data-original-title="{{ $contract['endlocation'] }}">
                                  <b>{{ str_limit($contract['endlocation'], 50, $end = '...') }}</b>
                                </span>
                                <span>
                                 ({{ number_format($contract['volume'], 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }} m<sup>3</sup>)
                                </span>
                              </li>
                              @if(isset($contract['title']) && strlen($contract['title']) > 0)
                                <li>
                                  <i class="fa fa-bullhorn" data-original-title=" {{ $contract['title'] }}" title="" data-toggle="tooltip"></i>
                                  Title: <b>{{ str_limit($contract['title'], 100, $end = '...') }}</b>
                                </li>
                              @endif
                              <li>
                                <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateIssued'] }}" title="" data-toggle="tooltip"></i>
                                Issued: <b>{{ Carbon\Carbon::parse($contract['dateIssued'])->diffForHumans() }}</b>
                              </li>

                              {{-- Add a conditionnal check. If a contract is not completed we show the expiration date else nothing --}}
                              @if(!isset($contract['dateCompleted']))
                                <li>
                                  <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateExpired'] }}" title="" data-toggle="tooltip"></i>
                                  Expires: <b>{{ Carbon\Carbon::parse($contract['dateExpired'])->diffForHumans() }}</b>
                                </li>
                              @endif

                              {{-- If a contract is accepted we show the date else nothing --}}
                              @if(isset($contract['dateAccepted']))
                                <li>
                                  <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateAccepted'] }}" title="" data-toggle="tooltip"></i>
                                  Accepted: <b>{{ Carbon\Carbon::parse($contract['dateAccepted'])->diffForHumans() }}</b>
                                  by
                                    {{ Seat\services\helpers\Img::html($contract['acceptorID'], 16, array('class' => 'img-circle eveIcon small')) }}
                                    <b><span rel="id-to-name">{{ $contract['acceptorID'] }}</span></b>
                                </li>
                              @endif

                              {{-- If a contract is completed we show the date else nothing --}}
                              @if(isset($contract['dateCompleted']))
                                <li>
                                  <i class="fa fa-clock-o" data-original-title=" {{ $contract['dateCompleted'] }}" title="" data-toggle="tooltip"></i>
                                  Completed: <b>{{ Carbon\Carbon::parse($contract['dateCompleted'])->diffForHumans() }}</b>
                                </li>
                              @endif
                              <li>
                                <i class="fa fa-money"></i>
                                <b>{{ number_format($contract['reward'], 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</b> ISK in reward
                              </li>
                              <li>
                                <i class="fa fa-money"></i>
                                <b>{{ number_format($contract['collateral'], 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</b> ISK in collateral
                              </li>
                            </ul>
                          </div><!-- ./col-md-12 -->

                        @endforeach

                    </div><!-- ./col-md-12 -->
                  </div><!-- ./row -->
                </div><!-- ./box-body -->
              </div><!-- ./box -->
            </div> <!-- ./col-md-6 -->

          </div><!-- ./row -->
        </div><!-- /.box-body -->
      </div><!-- ./box -->

    </div> <!-- ./col-md-12 -->
  </div> <!-- ./row -->

@stop

@section('javascript')
  <script type="text/javascript">

    // First Hide all contents. Not very clean to add a fake class.. TODO: Think another way to do this
    $(".tbodycontent").hide();
    // on button click. Not very clean to add a fake class.. TODO: Think another way to do this
    $(".viewcontent").on("click", function( event ){

      // get the tag direct after the button
      if($(this).hasClass('contracts')){
        // if we are in Contracts view, we check the next Div Tag
        var contents = $(this).closest( "table").next( "div" );
      } else {
        // if we are in Asset view, we check the next Tbody tag
        var contents = $(this).closest( "tbody").next( "tbody" );
      }

      // Show or hide
      contents.toggle();
      setupLazyLoader(contents);

      // some code for stylish
      if (contents.is(":visible")){
        $(this).removeClass('fa-plus').addClass('fa-minus');
        $(this).closest("tr").css( "background-color", "#EBEBEB" ); // change the background color of container (for easy see where we are)
        contents.css( "background-color", "#EBEBEB" ); // change the background color of content (for easy see where we are)
      } else {
        $(this).removeClass('fa-minus').addClass('fa-plus');
        $(this).closest("tr").css( "background-color", "#FFFFFF" ); // reset the background color on container when we hide content
      }
    });

    // id-to-name resolutions
    $( document ).ready(function() {
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
