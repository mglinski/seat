@extends('layouts.masterLayout')

@section('html_title', 'Corporation Jobs')

@section('page_content')

  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Corporation Jobs for {{ $corporation->corporationName }}</h3>
        </div>
        <div class="box-body no-padding">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#runningJobs" data-toggle="tab">Running</a>
              </li>
              <li>
                <a href="#endedJobs" data-toggle="tab">Ended</a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="runningJobs">
                <table class="table table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>Launch</th>
                      <th>End</th>
                      <th>Type</th>
                      <th>Progress</th>
                      <th>Installer</th>
                      <th>Runs</th>
                      <th>Blueprint</th>
                      <th>Product</th>
                      <th>Station Name</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($current_jobs as $job)

                      <tr>
                        <td>
                          <span data-toggle="tooltip" data-placement="top" title="{{ $job->startDate }}">{{ Carbon\Carbon::parse($job->startDate)->diffForHumans() }}</span>
                        </td>
                        <td>
                          <span data-toggle="tooltip" data-placement="top" title="{{ $job->endDate }}">{{ Carbon\Carbon::parse($job->endDate)->diffForHumans() }}</span>
                        </td>
                        <td>
                          @if ($job->activityID == 1)
                            <span class="label bg-orange">Manufacturing</span>
                          @elseif ($job->activityID == 3)
                            <span class="label bg-green">TE</span>
                          @elseif ($job->activityID == 4)
                            <span class="label bg-green">ME</span>
                          @elseif ($job->activityID == 5)
                            <span class="label bg-red">Copying</span>
                          @elseif ($job->activityID == 7)
                            <span class="label label-default">Reverse</span>
                          @else
                            <span class="label bg-aqua">Invention</span>
                          @endif
                        </td>
                        <td>
                          <div class="progress md progress-striped active">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: {{ (\Carbon\Carbon::now()->timestamp - \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $job->startDate)->timestamp) / $job->timeInSeconds * 100 }}%; position:relative;">
                              <span style="position:absolute; color:black;">
                              {{ number_format((Carbon\Carbon::now()->timestamp - \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $job->startDate)->timestamp) / $job->timeInSeconds * 100, 2, $settings['decimal_seperator'], $settings['thousand_seperator']) }} % Complete
                              </span>
                            </div>
                          </div>
                        </td>
                        <td>
                            {{ Seat\services\helpers\Img::character($job->installerID, 16, array('class' => 'img-circle eveIcon small')) }}
                            {{ $job->installerName }}
                        </td>
                        <td>{{ $job->runs }}</td>
                        <td>
                            {{ Seat\services\helpers\Img::type($job->blueprintTypeID, 16, array('class' => 'eveIcon small')) }}
                            {{ $job->blueprintTypeName }}
                        </td>
                        <td>
                            {{ Seat\services\helpers\Img::type($job->productTypeID, 16, array('class' => 'eveIcon small')) }}
                            {{ $job->productTypeName }}
                        </td>
                        <td>{{ $job->location }}</td>
                      </tr>

                    @endforeach

                  </tbody>
                </table>
              </div>

              <div class="tab-pane" id="endedJobs">
                <table class="table table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>Launch</th>
                      <th>End</th>
                      <th>Type</th>
                      <th>Installer</th>
                      <th>Runs</th>
                      <th>Blueprint</th>
                      <th>Product</th>
                      <th>Station Name</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($finished_jobs as $job)

                      <tr>
                        <td>
                          <span data-toggle="tooltip" data-placement="top" title="{{ $job->startDate }}">{{ Carbon\Carbon::parse($job->startDate)->diffForHumans() }}</span>
                        </td>
                        <td>
                          <span data-toggle="tooltip" data-placement="top" title="{{ $job->endDate }}">{{ Carbon\Carbon::parse($job->endDate)->diffForHumans() }}</span>
                        </td>
                        <td>
                          @if ($job->activityID == 1)
                            <span class="label bg-orange">Manufacturing</span>
                          @elseif ($job->activityID == 3)
                            <span class="label bg-green">TE</span>
                          @elseif ($job->activityID == 4)
                            <span class="label bg-green">ME</span>
                          @elseif ($job->activityID == 5)
                            <span class="label bg-red">Copying</span>
                          @elseif ($job->activityID == 7)
                            <span class="label label-default">Reverse</span>
                          @else
                            <span class="label bg-aqua">Invention</span>
                          @endif
                        </td>
                        <td>
                            {{ Seat\services\helpers\Img::character($job->installerID, 16, array('class' => 'img-circle eveIcon small')) }}
                            {{ $job->installerName }}
                        </td>
                        <td>{{ $job->runs }}</td>
                        <td>
                            {{ Seat\services\helpers\Img::type($job->blueprintTypeID, 16, array('class' => 'eveIcon small')) }}
                            {{ $job->blueprintTypeName }}
                        </td>
                        <td>
                            {{ Seat\services\helpers\Img::type($job->productTypeID, 16, array('class' => 'eveIcon small')) }}
                            {{ $job->productTypeName }}
                        </td>
                        <td>{{ $job->location }}</td>
                      </tr>

                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@stop
