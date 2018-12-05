<?php
require __DIR__."/../vendor/autoload.php";

class Test{
    public function __construct()
    {

        $client = new \Jiji\Http\Client();
        $result = $client->get("https://www.apiopen.top/weatherApi", ['city'=>'成都']);
        var_dump($result);
    }
}

new Test();