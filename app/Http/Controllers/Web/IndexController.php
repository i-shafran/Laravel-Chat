<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

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
		return view('layouts.index');
	}

}
