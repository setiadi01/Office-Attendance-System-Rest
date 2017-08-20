<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @author  Setiadi, 20 Agustus 2017
 */

class ExampleController extends Controller
{
	/**
	 * Mengembalikan data user yang login
	 * @return Json
	 */
    public function getLoggedUser(){
    	return response()->json([
    		'status' => 'OK',
    		'data' => Auth::user()
    	]);
    }
}
