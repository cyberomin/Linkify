<?php 

namespace App\Http\Controllers;

use App\Links;
use Illuminate\Http\Request;

class LinkController extends Controller
{
	const OK 			= 200;
	const CREATED 		= 201;
	const BAD_REQUEST 	= 400;
	const SUCCESS 		= 'success';
	const ERROR 		= 'error';

	/**
	 * Show all URLs
	 * @return Response
	 */
	public function show() 
	{
		$links = Links::all();
		foreach ($links as $link) {
			$link['short_url'] = env('BASE_URL') ."/". $link->code;
		}
		$result = $this->getResult(self::SUCCESS, self::OK, ['links' => $links]);
		return response()->json($result, self::OK);
	}

	/**
	 * Create a new short link
	 * @param  Request $request request object
	 * @return json Response
	 */
	public function create(Request $request) 
	{

		if (empty($request) || empty(trim($request->url))) {
			$result = $this->getResult(self::ERROR, self::BAD_REQUEST, null, 'Invalid parameter sent');
			return response()->json($result, self::BAD_REQUEST);
		}

		if (!filter_var($request->url, FILTER_VALIDATE_URL)) {
			$result = $this->getResult(self::ERROR, self::BAD_REQUEST, null, 'Please provide a valid URL');
			return response()->json($result, self::BAD_REQUEST);
		}

		$url = $request->url;
		$link = Links::whereUrl($url)->first();
		if (!$link) {
			$code = "";
			$link = Links::create(['url' => $request->url, 'code' => $code]);

			$code = base_convert($link->id + 10000, 10, 36);
			$link = Links::whereUrl($url)->update([
				'code' => $code
			]);
		}

		$code = isset($code) ?  $code : $link->code;
		$data = [
			'link' => [
				'code' => $code, 
				'url' => env('BASE_URL') ."/". $code,
				'long_url' => $url
			]
		];
		$result = $this->getResult(self::SUCCESS, self::CREATED, $data);
		return response()->json($result, self::CREATED);
		
	}

	/**
	 * Build Result
	 * @param  string     $status  status type
	 * @param  integer    $code    response code
	 * @param  array|null $data    result
	 * @param  string     $message error message
	 * @return array      $result
	 */
	public function getResult($status, $code, array $data = null, $message = null) 
	{
		$result = [];
		$result['status'] = $status;
		$result['code'] = $code;

		if (!is_null($data)) {
			$result['data'] = $data;
		}
		
		if (!is_null($message)) {
			$result['message'] = $message;
		}
		return $result;
	}
}