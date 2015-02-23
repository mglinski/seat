 {{-- character standings --}}
 <div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Standings</h3>
      </div><!-- /.box-header -->
      <div class="box-body no-padding">

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
                        {{ Seat\services\helpers\Img::html($standing->fromID, 16, array('class' => 'img-circle eveIcon small')) }}
                        {{ $standing->fromName }}
                    </td>
                    <td>{{ $standing->standing }}</td>
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
                        {{ Seat\services\helpers\Img::html($standing->fromID, 16, array('class' => 'img-circle eveIcon small')) }}
                        {{ $standing->fromName }}
                    </td>
                    <td>{{ $standing->standing }}</td>
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
                        {{ Seat\services\helpers\Img::html($standing->fromID, 16, array('class' => 'img-circle eveIcon small')) }}
                        {{ $standing->fromName }}
                    </td>
                    <td>{{ $standing->standing }}</td>
                  </tr>

                @endforeach

              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /.box-body -->
    </div>
  </div> <!-- ./col-md-12 -->
</div> <!-- ./row -->
