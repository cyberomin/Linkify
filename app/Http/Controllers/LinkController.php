<?php 

namespace App\Http\Controllers;

use App\Links;

class LinkController extends Controller
{
	const OK = 200;
	const SUCCESS = 'success';

	/**
	 * Show all URLs
	 * @return Response
	 */
	public function show() 
	{
		$links = Links::all();
		return response()->json(['status' => self::SUCCESS, 'data' => $links], self::OK);
	}

	public function create() 
	{
		$link = new Links;
    	$link->code = "y3g44";
	    $link->url = "http://man.com";
	    $link->save();

	    return "All done";
	}
}