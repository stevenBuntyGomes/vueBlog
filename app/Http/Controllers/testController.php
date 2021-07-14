<?php

namespace App\Http\Controllers;
use App\Tag;

use Illuminate\Http\Request;

class testController extends Controller
{
    public function welcome(){
        return view('welcome');
    }

    public function testTag(){
        return response()->json([
            'msg' => 'its working',
        ], 422);
    }

    public function bringTags(){

    }
}
