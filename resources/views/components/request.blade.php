<div class="my-2 shadow text-white bg-dark p-1" id="{{$mode}}">
    @if ($mode == 'sent')
        @foreach($sendRequests as $sendRequest)
            <div class="d-flex justify-content-between mb-2">
                <table class="ms-1">
                    <td class="align-middle">{{$sendRequest->receiver[0]->name}}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{$sendRequest->receiver[0]->email}}</td>
                    <td class="align-middle">
                </table>
                <div>
                    <button onclick="withdrawRequest('{{$sendRequest->id}}')" id="cancel_request_btn_sent" class="btn btn-danger me-1" onclick="">Withdraw Request</button>
                </div>
            </div>
        @endforeach
    @else
        @foreach($receivedRequests as $receivedRequest)
            <div class="d-flex justify-content-between mb-2">
                <table class="ms-1">
                    <td class="align-middle">{{$receivedRequest->sender[0]->name}}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{$receivedRequest->sender[0]->email}}</td>
                    <td class="align-middle">
                </table>
                <div>
                    <button onclick="acceptConnection('{{$receivedRequest->id}}')" id="accept_request_btn_" class="btn btn-primary me-1">Accept</button>
                </div>
            </div>
        @endforeach
    @endif
</div>