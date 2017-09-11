<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

/**
 * File Handler
 * @author  Setiadi, 10 September 2017
 */

class FileController extends Controller
{
    public function loadImage($image){
    	$imageFile = storage_path('app/images/profile-picture/'.$image);
           
        return Response::download($imageFile);   
    }
}
