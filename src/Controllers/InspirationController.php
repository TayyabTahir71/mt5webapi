<?php

namespace tayyabtahir71\MT5WebApi\Controllers;

use Illuminate\Http\Request;
use tayyabtahir71\MT5WebApi\Inspire;

class InspirationController {
    public function show(Inspire $inspire) {
        $quote = $inspire->justDoIt();
        return view('MT5WebApi::index', compact('quote'));
    }
    public function index(){

        $data = array(
            'here' => 'This is the home page - other views coming soon',
        );
        return view('MT5WebApi::index',$data);
    }
}
