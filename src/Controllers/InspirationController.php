<?php

namespace Fhsinchy\Inspire\Controllers;

use Illuminate\Http\Request;
use Fhsinchy\Inspire\Inspire;

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
