<?php

namespace App\Http\Controllers\Web;

use App\Events\MessageEvent;
use App\Events\PrivateChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class IndexController extends Controller
{
	/**
	 * IndexController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * dashboard page
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('chat');
	}
	
	public function sendMessages(Request $request)
	{
		MessageEvent::dispatch($request->all());
	}
	
	public function sendPrivateMessages(Request $request)
	{
		PrivateChatEvent::dispatch($request->all());
	}

	/**
	 * Get room
	 *
	 * @param $room_id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getRoom($room_id)
	{
		$room = Room::find($room_id);
		if($room == NULL){
			abort(404);
		}

		return view('room', ["room" => $room, "messages" => $room->messages]);
	}

}
