<?php

namespace Jiji\Http;



require "/Users/cuizhe/mfw/jijihttpclient/vendor/autoload.php";
class Test{
    public function __construct()
    {

        $client = new Client();
        //$client->get('http://servicedatabrand.mbrand.svc.ab/content/getdata?start=2018-11-01&end=20181130');
        //$a = $client->post('http://servicedatabrand.mbrand.svc.ab/content/getdata?start=2018-11-01&end=20181130');
        //$c = $client->requestRaw('http://servicedatabrand.mbrand.svc.ab/content/getdata?start=2018-11-01&end=20181130');

var_dump($c);


        exit;
    }
}

new Test();