<div class="row justify-content-center">
  <div class="col-12">
    <div class="card shadow  text-white bg-dark">
      <div class="card-header">Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" {{  $type == 'suggestions' ? 'checked':'' }}>
          <a href="{{ route('home',['type'=>'suggestions']) }}" class="btn btn-outline-primary" for="btnradio1" id="get_suggestions_btn">Suggestions ({{$suggestions->total()}})</a>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" {{  $type == 'sent' ? 'checked':'' }}>
          <a href="{{ route('home',['type'=>'sent']) }}" class="btn btn-outline-primary" for="btnradio2" id="get_sent_requests_btn">Sent Requests ({{$sendRequests->total()}})</a>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"{{  $type == 'received' ? 'checked':'' }}>
          <a href="{{ route('home',['type'=>'received']) }}" class="btn btn-outline-primary" for="btnradio3" id="get_received_requests_btn">Received
            Requests({{$receivedRequests->total()}})</a>

          <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" {{  $type == 'connections' ? 'checked':'' }}>
          <a href="{{ route('home',['type'=>'connections']) }}" class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">Connections ({{$connections->total()}})</a>
        </div>
        <hr>

        @if($type=='sent')
          <x-request :mode="'sent'" :sendRequests="$sendRequests" :receivedRequests="$receivedRequests"/>
          <div id="skeleton_sent" class="d-none">
              @for ($i = 0; $i < 10; $i++)
                <x-skeleton />
              @endfor
          </div>

          <div class="d-flex justify-content-center mt-2 py-3 {{$sendRequests->lastPage() == $sendRequests->currentPage()? 'd-none':''}}" id="load_more_btn_parent_sent">
              <button class="btn btn-primary" onclick="loadMore('{{$type}}');" id="load_more_btn_sent">Load more</button>
          </div>
        @endif
        @if($type=='received')
          <x-request :mode="'received'" :sendRequests="$sendRequests" :receivedRequests="$receivedRequests"/>
          <div id="skeleton_received" class="d-none">
              @for ($i = 0; $i < 10; $i++)
                <x-skeleton />
              @endfor
          </div>

          <div class="d-flex justify-content-center mt-2 py-3 {{$receivedRequests->lastPage() == $receivedRequests->currentPage()? 'd-none':''}}" id="load_more_btn_parent_received">
              <button class="btn btn-primary" onclick="loadMore('{{$type}}');" id="load_more_btn_received">Load more</button>
          </div>
        @endif
        @if($type=='suggestions')
          <x-suggestion :suggestions="$suggestions"/>
          <div id="skeleton_suggestions" class="d-none">
              @for ($i = 0; $i < 10; $i++)
                <x-skeleton />
              @endfor
          </div>

          <div class="d-flex justify-content-center mt-2 py-3 {{$suggestions->lastPage() == $suggestions->currentPage()? 'd-none':''}}"}}" id="load_more_btn_parent_suggestions">
              <button class="btn btn-primary" onclick="loadMore('{{$type}}');" id="load_more_btn_suggestions">Load more</button>
          </div>
        @endif
        @if($type=='connections')
          <x-connection :connections="$connections"/>
          <div id="skeleton_connections" class="d-none">
              @for ($i = 0; $i < 10; $i++)
                <x-skeleton />
              @endfor
          </div>
          <div class="d-flex justify-content-center mt-2 py-3 {{$connections->lastPage() == $connections->currentPage()? 'd-none':''}}" id="load_more_btn_parent_connections">
              <button class="btn btn-primary" onclick="loadMore('{{$type}}',{{auth()->user()->id}});" id="load_more_btn_connections">Load more</button>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    var page_no = 1;
    var has_more = true;
    function loadMore(type,user_id){
      if(has_more){
        $('#skeleton_'+type).removeClass('d-none');
        $.ajax({
          url: '{{ route('requests.index') }}',
          type: 'get',
          data: {
              "page": page_no + 1,
              "type": type
          },
          success: function (res) {
            $('#skeleton_'+type).addClass('d-none');

            var e = '';
            if(res.data.length){
              $.each(res.data, function(key,val) {
                if(type=='sent'){
                  e = e + '<div id="'+type+'_'+val.id+'" class="d-flex justify-content-between mb-2"><table class="ms-1"><td class="align-middle">'+val.receiver[0].name+'</td><td class="align-middle"> - </td><td class="align-middle">'+val.receiver[0].email+'</td><td class="align-middle"></div> </table><div><button id="cancel_request_btn_" class="btn btn-danger me-1" onclick=withdrawRequest('+val.id+')>Withdraw Request</button></div></div>';
                }
                if(type=='received'){
                  e = e + '<div class="d-flex justify-content-between mb-2"><table class="ms-1"><td class="align-middle">'+val.name+'</td><td class="align-middle"> - </td><td class="align-middle">'+val.email+'</td><td class="align-middle"></div> </table><div><button id="accept_request_btn_" class="btn btn-primary me-1" onclick="">Accept</button></div></div>';
                }
                if(type=='suggestions'){
                  e = e + '<div class="d-flex justify-content-between mb-2"><table class="ms-1"><td class="align-middle">'+val.name+'</td><td class="align-middle"> - </td><td class="align-middle">'+val.email+'</td><td class="align-middle"></div> </table><div><button onclick=handleAction('+val.id+') id="create_request_btn_" class="btn btn-primary me-1">Connect</button></div></div>';
                }
                if(type=='connections'){
                  // var commonConnections = Object.keys(val.commonConnections).map(function (key) { return val.commonConnections[key]; });
                  if(user_id != val.sender[0].id){
                    e = e + '<div class="d-flex justify-content-between mb-2"><table class="ms-1"><td class="align-middle">'+val.sender[0].name+'</td><td class="align-middle"> - </td><td class="align-middle">'+val.sender[0].email+'</td><td class="align-middle"></div> </table><div><div><button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">Connections in common ('+val.commonConnections.data.length+')</button> <button id="create_request_btn_" onclick=withdrawRequest('+val.id+',"connection") class="btn btn-danger me-1">Remove Connection</button></div></div></div>';
                  }else{
                    e = e + '<div class="d-flex justify-content-between"><table class="ms-1"><td class="align-middle">'+val.receiver[0].name+'</td><td class="align-middle"> - </td><td class="align-middle">'+val.receiver[0].email+'</td><td class="align-middle"></div> </table><div><div><button style="width: 220px" id="get_connections_in_common_" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">Connections in common ('+val.commonConnections.data.length+')</button> <button id="create_request_btn_" onclick=withdrawRequest('+val.id+',"connection") class="btn btn-danger me-1">Remove Connection</button></div></div></div>';
                  }
                }
            });   
          }
          $('#'+type).append(e);   
            page_no= page_no+1;
            if(res.last_page == page_no){
              has_more =false;
              $('#load_more_btn_parent_'+type).addClass('d-none');
            }
          },
          error: function (textStatus, errorThrown) {
            $('#skeleton_'+type).addClass('d-none');
          }
        });
      }
    }

    function handleAction(id){
      var csrf_js_var = $('meta[name="csrf-token"]').attr('content')
          $('<form>', {
              "id": "add-connection",
              "html": '<input type="text" id="id" name="id" value="' + id + '" /><input name="_token" value="'+csrf_js_var+'" type="hidden">',
              "action": '{{ route('requests.store') }}',
              'method': 'post'
          }).appendTo(document.body).submit();
    }

    function acceptConnection(id){
      let url = '{{ route('requests.update', ':id') }}';
      url = url.replace(':id', id);
      $.ajax({
          url: url,
          type: 'patch',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (res) {
            window.location.replace("/home?type=received");
          },
          error: function (textStatus, errorThrown) {
          }
        });
    }

    function withdrawRequest(id,type=null){
      let url = '{{ route('requests.destroy', ':id') }}';
      url = url.replace(':id', id);
      $.ajax({
          url: url,
          type: 'delete',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (res) {
            if(type){
              window.location.replace("/home?type=connections");
            }else{
              window.location.replace("/home?type=received");
            }
          },
          error: function (textStatus, errorThrown) {
          }
        });
    }
</script>