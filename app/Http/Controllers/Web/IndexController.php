<?php

namespace App\Http\Controllers\Web;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
	/**
	 * IndexController constructor.
	 */
	public function __construct()
	{
		//
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
		MessageEvent::dispatch($request->input('body'));
	}

}
