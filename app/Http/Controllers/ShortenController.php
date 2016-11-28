<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ShortenController extends Controller
{
    public function index(Request $r)
    {
    	if (session('url') != "") {
    		\Session::forget('url');
    	}
    	return view('shorten');
    }

    public function getUrl(Request $r)
    {
    	$shorten = $r->input('url');
    	if (strpos($shorten, 'http://') !== false) {
    	} else {
    		$shorten = 'http://'.$shorten;
    	}
    	// echo $shorten;
    	// die();
    	$url = env('API_URL');
    	$curl = new \Curl\Curl();

    	$curl->get($url.'v3/shorten', array(
    		'login' => env('API_LOGIN'),
    		'apiKey' => env('API_KEY'),
    		'longUrl' => $shorten,
    		'format' => 'json'
    	));

    	if ($curl->error) {
    		die("Error: ".$curl->error_code);
    	} else {
    		$json = json_decode($curl->response);
    	}

    	$url = $json->data->url;
    	\Session::put('url', $url);
    	return view('shorten');
    }
}
