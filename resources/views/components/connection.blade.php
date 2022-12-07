<div class="my-2 shadow text-white bg-dark p-1 mb-2" id="connections">
    @foreach($connections as $connection)
        @if(auth()->user()->id != $connection->sender[0]->id)
            <div class="d-flex justify-content-between">
                <table class="ms-1">
                    <td class="align-middle">{{$connection->sender[0]->name}}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{$connection->sender[0]->email}}</td>
                    <td class="align-middle">
                </table>
                <div>
                    <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
                      data-bs-toggle="collapse" data-bs-target="#collapse_{{$connection->id}}" aria-expanded="false" aria-controls="collapseExample">
                      Connections in common ({{$connection->commonConnections->total()}})
                    </button>
                    <button id="create_request_btn_" onclick="withdrawRequest('{{$connection->id}}','connection')" class="btn btn-danger me-1">Remove Connection</button>
                </div>
            </div>
            <div class="collapse" id="collapse_{{$connection->id}}">
                <div id="content_{{$connection->id}}" class="p-2">
                    {{-- Display data here --}}
                    <x-connection_in_common :connection="$connection"/>
                </div>
                <div id="connections_in_common_skeletons_{{$connection->id}}" class="d-none">
                    @for ($i = 0; $i < 10; $i++)
                        <x-skeleton />
                    @endfor
                </div>
                <div class="d-flex justify-content-center w-100 py-2 {{$connection->commonConnections->lastPage() ==$connection->commonConnections->currentPage()? 'd-none':''}}" id="load_more_{{$connection->id}}">
                    <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_" onclick="loadMoreCommon('{{$connection->sender[0]->id}}','{{$connection->id}}');">Load
                    more</button>
                </div>
              </div>
        @else
            <div class="d-flex justify-content-between">
                <table class="ms-1">
                    <td class="align-middle">{{$connection->receiver[0]->name}}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{$connection->receiver[0]->email}}</td>
                    <td class="align-middle">
                </table>
                <div>
                    <button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button"
                      data-bs-toggle="collapse" data-bs-target="#collapse_{{$connection->id}}" aria-expanded="false" aria-controls="collapseExample">
                      Connections in common ({{$connection->commonConnections->total()}})
                    </button>
                    <button id="create_request_btn_" onclick="withdrawRequest('{{$connection->id}}','connection')" class="btn btn-danger me-1">Remove Connection</button>
                </div>
            </div>
            <div class="collapse" id="collapse_{{$connection->id}}">
                <div id="content_{{$connection->id}}" class="p-2">
                   <x-connection_in_common :connection="$connection"/>
                </div>
                <div id="connections_in_common_skeletons_{{$connection->id}}" class="d-none">
                    @for ($i = 0; $i < 10; $i++)
                      <x-skeleton />
                    @endfor
                </div>
                <div class="d-flex justify-content-center w-100 py-2 {{$connection->commonConnections->lastPage() ==$connection->commonConnections->currentPage()? 'd-none':''}}" id="load_more_{{$connection->id}}">
                    <button class="btn btn-sm btn-primary" id="load_more_connections_in_common_" onclick="loadMoreCommon('{{$connection->receiver[0]->id}}','{{$connection->id}}');">Load
                    more</button>
                </div>
            </div>
        @endif
   @endforeach
</div>

<script type="text/javascript">
    var page_numbers = [];  
    var have_more = [];
    function loadMoreCommon(user_id,id){
      if(page_numbers[id] == undefined){
        page_numbers[id] = 1;
        have_more[id] = true;
      }
      console.log(page_numbers[id])
      console.log(user_id,id)
      if(have_more[id]){
          $('#connections_in_common_skeletons_'+id).removeClass('d-none');
          $.ajax({
            url: '{{ route(requests.index) }}',
            type: 'get',
            data: {
                "page": page_numbers[id] + 1,
                "user_id":user_id,
                "type": 'common-connections'
            },
            success: function (res) {
              $('#connections_in_common_skeletons_'+id).addClass('d-none');

              var e = '';
              if(res.data.length){
                $.each(res.data, function(key,val) {
                    e = e + '<div class="p-2 shadow rounded mt-2  text-white bg-dark">'+val.name+' - '+val.email+'</div>';
                  });   
              }
              $('#content_'+id).append(e);   
                    page_numbers[id]= page_numbers[id]+1;
                    if(res.last_page == page_numbers[id]){
                      have_more[id] =false;
                      $('#load_more_'+id).addClass('d-none');
                    }
            },
            error: function (textStatus, errorThrown) {
              $('#connections_in_common_skeletons_'+id).addClass('d-none');
            }
          });
      }
    }
</script>