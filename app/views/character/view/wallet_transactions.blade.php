{{-- wallet transactions --}}
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Wallet Transactions ({{ count($wallet_transactions) }})</h3>
        <div class="box-tools">
          <a href="{{ action('CharacterController@getFullWalletTransactions', array('characterID' => $characterID)) }}" class="btn btn-default btn-sm pull-right">
            <i class="fa fa-money"></i> View Full Transactions
          </a>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">
        <table class="table table-condensed compact table-hover" id="datatable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>#</th>
              <th>Per Item</th>
              <th>Total</th>
              <th>Client</th>
              <th>Type</th>
              <th>Station Name</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($wallet_transactions as $e)

              <tr @if ($e->transactionType == 'buy')class="danger" @endif>
                <td data-order="{{ $e->transactionDateTime }}">
                  <span data-toggle="tooltip" title="" data-original-title="{{ $e->transactionDateTime }}">
                    {{ Carbon\Carbon::parse($e->transactionDateTime)->diffForHumans() }}
                  </span>
                </td>
                <td>
                    {{ Seat\services\helpers\Img::type($e->typeID, 16, array('class' => 'eveIcon small')) }}
                  {{ $e->typeName }}
                </td>
                <td>{{ $e->quantity }}</td>
                <td data-sort="{{ $e->price }}">
                  {{ number_format($e->price, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }} ISK
                </td>
                <td data-sort="{{ $e->price * $e->quantity }}">
                  {{ number_format($e->price * $e->quantity, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }} ISK
                </td>
                <td>{{ $e->clientName }}</td>
                <td>{{ $e->transactionType }}</td>
                <td>{{ $e->stationName }}</td>
              </tr>

            @endforeach

          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div>
  </div> <!-- ./col-md-12 -->
</div> <!-- ./row -->
