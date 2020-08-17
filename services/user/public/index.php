<?php

$original = array("A1", "A2", "A3", "B1", "B2", "B3", "C1", "C2", "C3");

$writeArray = array();
$count = 1;
foreach($) {
    $root = substr($value, 0, strlen($count));
    if(in_array($root.$count, $original)){
        $writeArray[$count-1] = $root.$count;
        $count++;
    }else{
        $count=0;
    }
}

$writeArray[] = ($value[$key]); 

array_walk($original, 'name', $writeArray);


//$at = array_multisort($original, SORT_ASC, SORT_STRING);

array_map(function ($a) {
}, $original);

print_r($original);
die();

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
