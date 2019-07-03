<?php
namespace App;

use App\OutputHandler as OutputHandler;
use App\HttpRequest as HttpRequest;

class CountryService extends OutputHandler
{
	protected $input = [];

	protected $url = ['https://restcountries.eu/rest/v2/name/','https://restcountries.eu/rest/v2/lang/'];

    protected $client;

	public function __construct(array $input, HttpRequest $client) 
    {
        $this->input = $input;
        $this->client = $client;
        $this->client->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->client->setOption(CURLOPT_SSL_VERIFYHOST, 0);
        $this->client->setOption(CURLOPT_SSL_VERIFYPEER, 0);
    }

    public function getResult(): string 
    {
        $response = $this->getResponse($this->url[0] . $this->input[1]); 
        $message = $this->inValidInput();
        if($response['status'] == '200') {
            $languageCode = $this->getLanguagesCode($response['content']);
            $sameLanguageCountry = $this->getSameLanguageCountry($languageCode);
            if(count($this->input) == 2 ) {
                $message = $this->showSameCountries(implode(' ', $languageCode), $this->input[1], $sameLanguageCountry) ;
            } else if(count($this->input) == 3) { 
               $message = $this->compareCountryLanguage($sameLanguageCountry); 
            } else {
                $message = $this->wrongParamsNumber();
            }
        }

        return $message;	
    }

    /**
    *  @param array $sameLanguageCountry
    *  @return string
    */
    private function compareCountryLanguage($sameLanguageCountry): string 
    {
        if(in_array($this->input[2], $sameLanguageCountry)) {
            $message = $this->isSameLanguage($this->input[1], $this->input[2]);
        } else {
            $message = $this->isNotSameLanguage($this->input[1], $this->input[2]);
        }

        return $message;
    }

    /**
     * @param json $content
     * @return array
     */
    private function getLanguagesCode($content): array
    {
        $languageCodeArray = json_decode($content, true)[0]['languages'];
        return array_column($languageCodeArray, 'iso639_1') ;
    }

    /**
    * @param array $languageCode
    * @return array
    */
    private function getSameLanguageCountry($languageCode): array
    {
        $countryList = [];
        foreach ($languageCode as $lang) {
            $response = $this->getResponse($this->url[1] . $lang); ;
            $countries = json_decode($response['content'], true);
            foreach ($countries as $country) {
                if (isset($country['name']) and $country['name'] != $this->input[1]) {
                    $countryList[] = $country['name'];
                }
            }
        }

        return $countryList;
    }

    /**
    * @param string $url
    *
    */
    private function getResponse($url): array
    {
        $this->client->setOption(CURLOPT_URL, $url);
		$result = $this->client->execute();
        $httpStatus = $this->client->getInfo(CURLINFO_HTTP_CODE);

 		return ['status' => $httpStatus, 'content' => $result];
    }
}