@extends('layouts.masterLayout')

@section('html_title', 'All Corporation Starbases')

@section('page_content')

  @foreach ($corporations as $corp)

    <div class="col-md-4">
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{ $corp->corporationName }}</h3>
          <p>From character: {{ $corp->characterName }}</p>
        </div>
        <div class="icon">
            {{ Seat\services\helpers\Img::corporation($corp->corporationID, 32, array('class' => 'img-circle eveIcon medium')) }}
        </div>
        <a href="{{ action('CorporationController@getStarbase', array('corporationID' => $corp->corporationID)) }}" class="small-box-footer">
          View Corporation Starbases <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>

  @endforeach

@stop
