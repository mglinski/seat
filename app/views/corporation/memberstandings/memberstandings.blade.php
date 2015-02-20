@extends('layouts.masterLayout')

@section('html_title', 'Corporation Standings')

@section('page_content')

  <div class="box">
    <div class="box-header">
      <h3 class="box-title">All Standings</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-4">
          <table class="table table-hover table-condensed compact" id="datatable">
            <thead>
              <tr>
                <td>Agent Name</td>
                <td>Standing</td>
              </tr>
            </thead>
            <tbody>

              @foreach($agent_standings as $standing)

                <tr>
                  <td>
                    <img src='{{ URL::asset('assets/img/bg.png') }}'
                         data-src="{{ App\Services\Helpers\Helpers::generateEveImage($standing->fromID, 32) }}"
                         data-src-retina="{{ App\Services\Helpers\Helpers::generateEveImage($standing->fromID, 64) }}"
                         class='img-circle' style='width: 18px;height: 18px;'>
                    {{ $standing->fromName }}
                  </td>
                  <td>{{ number_format($standing->standing, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</td>
                </tr>

              @endforeach

            </tbody>
          </table>
        </div>

        <div class="col-md-4">
          <table class="table table-hover table-condensed compact" id="datatable">
            <thead>
              <tr>
                <td>NPC Corporation Name</td>
                <td>Standing</td>
              </tr>
            </thead>
            <tbody>

              @foreach($npc_standings as $standing)

                <tr>
                  <td>
                    <img src='{{ URL::asset('assets/img/bg.png') }}'
                         data-src="{{ App\Services\Helpers\Helpers::generateEveImage($standing->fromID, 32) }}"
                         data-src-retina="{{ App\Services\Helpers\Helpers::generateEveImage($standing->fromID, 64) }}"
                         class='img-circle' style='width: 18px;height: 18px;'>
                    {{ $standing->fromName }}
                  </td>
                  <td>{{ number_format($standing->standing, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</td>
                </tr>

              @endforeach

            </tbody>
          </table>
        </div>

        <div class="col-md-4">
          <table class="table table-hover table-condensed compact" id="datatable">
            <thead>
              <tr>
                <td>Faction Name</td>
                <td>Standing</td>
              </tr>
            </thead>
            <tbody>

              @foreach($faction_standings as $standing)

                <tr>
                  <td>
                    <img src='{{ URL::asset('assets/img/bg.png') }}'
                         data-src="{{ App\Services\Helpers\Helpers::generateEveImage($standing->fromID, 32) }}"
                         data-src-retina="{{ App\Services\Helpers\Helpers::generateEveImage($standing->fromID, 64) }}"
                         class='img-circle' style='width: 18px;height: 18px;'>
                    {{ $standing->fromName }}
                  </td>
                  <td>{{ number_format($standing->standing, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</td>
                </tr>

              @endforeach

            </tbody>
          </table>
        </div>
      </div>

    </div><!-- ./ box-body -->
  </div><!-- ./box -->

@stop
