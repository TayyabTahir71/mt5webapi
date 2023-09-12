<?php

namespace TayyabTahir71\MT5WebApi;

include "MTWebAPI.php";

class MT5WebApi
{
    public $api;

    public function __construct()
    {
        $this->api = new MTWebAPI('WebAPI', config("metaquotes.log_file_location"));
    }

    public function getApiInstance(){
        return $this->api;
    }
}