<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $type= $request->query('type') ? $request->query('type') : 'suggestions';
        $user = Auth::user();

        $sendRequests = Connection::where('sender_id', $user->id)->with('receiver')->where('status', 'requested')->paginate(10);

        $receivedRequests = Connection::where('receiver_id', $user->id)->with('sender')->where('status', 'requested')->paginate(10);
        
        $connections = Connection::where('status', 'accepted')->where(function($q) use($user) {
            $q->where('receiver_id', $user->id)->orWhere('sender_id', $user->id);
        })->with(['sender','receiver'])->paginate(10);

        $connectedUsers = Connection::where('status', 'accepted')->where(function($q) use($user) {
            $q->where('receiver_id', $user->id)->orWhere('sender_id', $user->id);
        })->get();

        $connectedUserIds= [];
        foreach($connectedUsers as $connectedUser){
            if($connectedUser->receiver_id != $user->id){
                array_push($connectedUserIds, $connectedUser->receiver_id);
            }
            if($connectedUser->sender_id != $user->id){
                array_push($connectedUserIds, $connectedUser->sender_id);
            }
        }
        foreach($connections as $connection){
            $connected_ids=[];
            $userConnections=[];
            if($connection->receiver_id != $user->id){
                $userConnections = Connection::where('sender_id', '!=', $user->id)->where('receiver_id', '!=', $user->id)->where(function($q) use($connection) {
                    $q->where('receiver_id', $connection->receiver_id)->orWhere('sender_id', $connection->receiver_id);
                })->where('status',2)->get();
            }
            if($connection->sender_id != $user->id){
                $userConnections = Connection::where('sender_id', '!=', $user->id)->where('receiver_id', '!=', $user->id)->where(function($q) use($connection) {
                    $q->where('receiver_id',$connection->sender_id)->orWhere('sender_id',$connection->sender_id);
                })->where('status',2)->get();
            }
            foreach($userConnections as $userConnection){
                if($userConnection->receiver_id != $user->id && ($userConnection->receiver_id != $connection->receiver_id && $userConnection->receiver_id != $connection->sender_id)){
                    array_push($connected_ids,$userConnection->receiver_id);
                }
                if($userConnection->sender_id != $user->id && ($userConnection->sender_id != $connection->sender_id && $userConnection->sender_id != $connection->receiver_id)){
                    array_push($connected_ids,$userConnection->sender_id);
                }
            }
            $commonConnectionIds=array_intersect($connectedUserIds, $connected_ids);
            $connection->commonConnections = User::whereIn('id', $commonConnectionIds)->where('id', '!=', $user->id)->paginate(10);
        }

        $allConnections = Connection::where('receiver_id', $user->id)->orWhere('sender_id', $user->id)->get();
        $connectionUserIds=[];
        foreach($allConnections as $connection){
            if($connection->receiver_id != $user->id){
                array_push($connectionUserIds, $connection->receiver_id);
            }
            if($connection->sender_id != $user->id){
                array_push($connectionUserIds, $connection->sender_id);
            }
        }

        $suggestions = User::whereNotIn('id', $connectionUserIds)->where('id', '!=', $user->id)->paginate(10);
        return view('home',compact('suggestions', 'sendRequests', 'receivedRequests', 'connections', 'type'));
    }
}
