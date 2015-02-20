@extends('layouts.masterLayout')

@section('html_title', 'All Corporations Journals')

@section('page_content')

  @foreach ($corporations as $corp)

    <div class="col-md-4">
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{ $corp->corporationName }}</h3>
          <p>From character: {{ $corp->characterName }}</p>
        </div>
        <div class="icon">
          <img src='{{ URL::asset('assets/img/bg.png') }}'
               data-src="{{ App\Services\Helpers\Helpers::generateEveImage($corp->corporationID, 32) }}"
               data-src-retina="{{ App\Services\Helpers\Helpers::generateEveImage($corp->corporationID, 64) }}"
               class="img-circle" />
        </div>
        <a href="{{ action('CorporationController@getJournal', array('corporationID' => $corp->corporationID)) }}" class="small-box-footer">
          View Corporation Wallet Journal <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>

  @endforeach

@stop
