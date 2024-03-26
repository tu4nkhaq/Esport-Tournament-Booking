<x-layout>
    <x-labels>
        <a class="active" href="#">Home</a>
    </x-labels>
    <ul class="box-info">
        <li>
            <i class='bx bx-joystick'></i>
            <span class="text">
                <h3>{{$overall_tournaments->count()}}</h3>
                <p>Tournaments</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-group' ></i>
            <span class="text">
                <h3>{{$users->count()}}</h3>
                <p>Users</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-calendar-check' ></i>
            <span class="text">
                <h3>{{$bookings->count()}}</h3>
                <p>Bookings</p>
            </span>
        </li>
    </ul>
    <x-table>
        <div class="head">
            <h3> Recent Tournaments</h3>

        </div>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Name</th>
                <th>Type</th>
                <th>Game</th>
                <th>Fees</th>
                <th>Team Size</th>
                <th>Prize Pool</th>
                <th>Closing Time</th>
            </tr>
        </thead>
        <tbody>
           @if ($tournaments->count()==0)
           <tr>
            <td colspan="4">
                No data found
            </td>
            </tr>
           @else
          @foreach ($tournaments as $tournament)
          <tr>
            <td>
                {{$loop->iteration}}
            </td>
            <td>
                <div class="tournament-image">
                    <img src="{{asset('storage/'.$tournament->image->image)}}" alt="tournament-image">
                {{$tournament->name}}
                </div>

            </td>
            <td>
                {{$tournament->type}}
            </td>
            <td>
                {{$tournament->game->name}}
            </td>
            <td>
                {{$tournament->fees}}
            </td>
            <td>
                {{$tournament->team_size}}
            </td>
            <td>
                {{$tournament->prize_pool}}
            </td>
            <td>
                {{$tournament->closing_time}}
            </td>
            </tr>
          @endforeach

           @endif
        </tbody>
    </x-table>
    <div class="pagination d-flex justify-content-end mt-5">
        <ul class="pagination">
            <li class="page-item">
                {{ $tournaments->links('vendor.pagination.simple-tailwind') }}
            </li>
        </ul>
    </div>
</x-layout>
