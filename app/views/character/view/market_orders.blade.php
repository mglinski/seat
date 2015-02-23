{{-- character market orders --}}
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Market Orders ({{ count($market_orders) }})</h3>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">
        <table class="table table-hover table-condensed compact" id="datatable">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Type</th>
              <th>Price P/U</th>
              <th>Issued</th>
              <th>Expires</th>
              <th>Order By</th>
              <th>Location</th>
              <th>Type</th>
              <th>Vol</th>
              <th>Min. Vol</th>
              <th>State</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($market_orders as $order)

              <tr>
                <td>{{ $order->orderID }}</td>
                <td>
                  @if ($order->bid)
                    Buy
                  @else
                    Sell
                  @endif
                </td>
                <td data-sort="{{ $order->price }}">
                  @if ($order->escrow > 0)
                    <span data-toggle="tooltip" title="" data-original-title="Escrow: {{ $order->escrow }}">
                      <i class="fa fa-money pull-right"></i> {{ number_format($order->price, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}
                    </span>
                  @else
                    {{ number_format($order->price, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}
                  @endif
                </td>
                <td>{{ $order->issued }}</td>
                <td data-order="{{ Carbon\Carbon::parse($order->issued)->addDays($order->duration) }}">
                  {{ Carbon\Carbon::parse($order->issued)->addDays($order->duration)->diffForHumans() }}
                </td>
                <td>
                  <a href="{{ action('CharacterController@getView', array('characterID' => $order->charID)) }}">
                      {{ Seat\services\helpers\Img::character($order->charID, 16, array('class' => 'img-circle eveIcon small')) }}
                  </a>
                  <span rel="id-to-name">{{ $order->charID }}</span>
                </td>
                <td>{{ $order->location }}</td>
                <td>
                  {{ Seat\services\helpers\Img::type($order->typeID, 16, array('class' => 'eveIcon small')) }}
                  {{ $order->typeName }}
                </td>
                <td>{{ $order->volRemaining }}/{{ $order->volEntered }}</td>
                <td>{{ $order->minVolume }}</td>
                <td>{{ $order_states[$order->orderState] }}</td>
              </tr>

            @endforeach

          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div> <!-- ./col-md-12 -->
</div> <!-- ./row -->
