<?php
namespace App;

class OutputHandler
{

	public function showSameCountries($languageCode,$chosenCountry, $countries)
	{		
		return "Country language code: $languageCode \n $chosenCountry speaks same language with these countries: " . implode(', ', $countries) ;    
	}
	
	public function isSameLanguage($firstCountry, $secondCountry)
	{
		return "$firstCountry and $secondCountry do speak the same language";
	}

	public function isNotSamelanguage($firstCountry, $secondCountry)
	{
		return "$firstCountry and $secondCountry do not speak the same language";
	}

	public function wrongParamsNumber()
	{
		return "Please insert only one or two valid Countries name";
	}

	public function invalidInput()
	{
		return "Please insert valid Country Name";
	}
}