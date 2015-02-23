@extends('layouts.masterLayout')

@section('html_title', 'Character Wallet Transactions')

@section('page_content')

  <div class="row">
    <div class="col-md-12">

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">
            Wallet Transactions for:
            <a href="{{ action('CharacterController@getView', array('characterID' => $characterID)) }}">
              <img src='{{ URL::asset('assets/img/bg.png') }}'
                   data-src="//image.eveonline.com/Character/{{ $characterID }}_32.jpg"
                   data-src-retina="//image.eveonline.com/Character/{{ $characterID }}_64.jpg"
                   class='img-circle' style='width: 18px;height: 18px;'>
            </a>
            {{ $character_name }}
          </h3>
          <div class="box-tools">
            <ul class="pagination pagination-sm no-margin pull-right">
              {{ $wallet_transactions->links() }}
            </ul>
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
                    <img src='{{ URL::asset('assets/img/bg.png') }}'
                         data-src="//image.eveonline.com/Type/{{ $e->typeID }}_32.png"
                         data-src-retina="//image.eveonline.com/Type/{{ $e->typeID }}_64.png"
                         style='width: 18px;height: 18px;'>
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
        <div class="pull-right">{{ $wallet_transactions->links() }}</div>
      </div>
    </div>
  </div>

@stop
