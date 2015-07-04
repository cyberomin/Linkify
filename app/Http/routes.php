<?php



$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers'], function() use ($app) {

    $app->get('links', [
    	'as' => 'links', 'uses' => 'LinkController@show'
	]);

});