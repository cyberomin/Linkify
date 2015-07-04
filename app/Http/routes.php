<?php

$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function() use ($app) {

	/**
	 * Get all the available URLS
	 */
    $app->get('links', [
    	'as' => 'links', 'uses' => 'LinkController@show'
	]);

	$app->post('links', [
    	'as' => 'create', 'uses' => 'LinkController@create'
	]);

});