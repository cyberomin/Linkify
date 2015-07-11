<?php 

namespace App\Http\Controllers;

use App\Links;
use Illuminate\Http\Request;

class LinkController extends Controller
{
	const OK 			= 200;
	const CREATED 		= 201;
	const CONTENT_NOT_FOUND = 204;
	const BAD_REQUEST 	= 400;
	const NOT_FOUND 	= 404;
	const SUCCESS 		= 'success';
	const ERROR 		= 'error';

	/**
	 * Show all URLs
	 * @return Response
	 */
	public function showAll(Request $request) 
	{
		$links = Links::all();
		foreach($links as $link) {
			$link['short_url'] = stripslashes(env('BASE_URL') ."/". $link->code);
		}
		
		$result = $this->getResult(self::SUCCESS, self::OK, ['links' => $links]);
		return response()->json($result, self::OK);
	}

	/**
	 * Show url with this code
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function show($code)
	{
		$link = Links::whereCode($code)->first();
		if ($link) {

			$data = ['hash' => $link->code, 'url' => env('BASE_URL') ."/". $link->code, 'long_url' => $link->url ];
			$result = $this->getResult(self::SUCCESS, self::OK, $data, null);
			return response()->json($result, self::OK);
		}

		$result = $this->getResult(self::ERROR, self::NOT_FOUND, null, 'Item was not found');
		return response()->json($result, self::NOT_FOUND);
	}

	/**
	 * Delete URL
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function delete($code)
	{
		$link = Links::whereCode($code)->first();
		if ($link) {

			$link->delete();
			$data = ['message' => 'Item has been deleted'];
			$result = $this->getResult(self::SUCCESS, self::CONTENT_NOT_FOUND, $data);
			return response()->json($result, self::OK);
		}

		$result = $this->getResult(self::ERROR, self::NOT_FOUND, null, 'Item was not found');
		return response()->json($result, self::NOT_FOUND);
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


			$code = base_convert($link->id + rand(10000,100000), 10, 36);
			$link = Links::whereUrl($url)->update([
				'code' => $code
			]);
		}

		$code = isset($code) ?  $code : $link->code;
		$data = [
			'link' => [
				'hash' => $code, 
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
	public function getResult($status, $code, array $data = null, $message = null, array $paginate = null) 
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

		if (!is_null($paginate)) {
			$result['paginator'] = $paginate;
		}
		return $result;
	}

	/**
	 * Redirect to the URL
	 * @param  string $code URL hash
	 * @return [type]       [description]
	 */
	public function redirect($code) 
	{
		$link = Links::whereCode($code)->first();
		header("Location:" . $link->url);
	}
}