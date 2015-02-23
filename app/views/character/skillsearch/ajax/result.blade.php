@if (count($filter) > 0)

  <table class="table table-condensed compact table-hover" id="datatable">
    <thead>
      <tr>
        <th>Character</th>
        <th>Corporation</th>
        <th>Skill</th>
        <th>Skillpoints</th>
        <th>Level</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($filter as $result)

        <tr>
          <td>
            <a href="{{ action('CharacterController@getView', array('characterID' => $result->characterID)) }}">
                {{ Seat\services\helpers\Img::character($result->characterID, 16, array('class' => 'img-circle eveIcon small')) }}
                {{ $result->characterName }}
            </a>
          </td>
          <td>{{ $result->corporationName }}</td>
          <td>{{ $result->typeName }}</td>
          <td>{{ number_format($result->skillpoints, 0, $settings['decimal_seperator'], $settings['thousand_seperator']) }}</td>
          <td>

            {{ $result->level }} |
            {{-- copied from character.view --}}
            @if ($result->level == 0)
              <i class="fa fa-star-o"></i>
            @elseif ($result->level == 5)
              <span class="text-green">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </span>
              @else
                @for ($i=0; $i < $result->level ; $i++)
                  <i class="fa fa-star"></i>
                @endfor
              @endif
            </td>
          </tr>

        @endforeach

      </tbody>
    </table>
  @else
    <div class="callout callout-warning">
      <h4>No Results</h4>
      <p>Your skills specific search yielded no results.</p>
  </div>
@endif
