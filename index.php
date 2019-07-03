<?php 
require_once __DIR__ . '/vendor/autoload.php';

use App\CountryService as CountryService;
use App\CurlRequest as CurlRequest;

$countriesService = new CountryService($argv, new CurlRequest());

print_r($countriesService->getResult());
