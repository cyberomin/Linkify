<?php 

namespace App\Http\Controllers;

use App\Links;
use Illuminate\Http\Request;

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
		$result = [
			'status' => self::SUCCESS, 
			'data' => [ 'links' => $links]
		];
		return response()->json($result, self::OK);
	}

	/**
	 * Create a new URL
	 * @return Response
	 */
	public function create(Request $request) 
	{
		if (empty(trim($request->url))) {

		}
	}
}