@foreach($connection->commonConnections as $common)
    <div class="p-2 shadow rounded mt-2  text-white bg-dark">{{$common->name}} - {{$common->email}}</div>
@endforeach