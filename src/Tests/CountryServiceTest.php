<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\CountryService as CountryService;

class CountryServiceTest extends TestCase 
{
	protected $countryService;
	protected $client;

	public function setUp()
    {
    	$this->client =  $this->getMockBuilder('App\HttpRequest')
            ->disableOriginalConstructor()
            ->getMock();
    	$this->client->expects($this->any())
 		->method('getInfo')
     	->will($this->returnValue('200'));

         $this->client->expects($this->exactly(2))
         ->method('execute')
         ->willReturnOnConsecutiveCalls('[{"name":"Germany","region":"Europe","subregion":"Western Europe","population":81770900,"languages":[{"iso639_1":"de","iso639_2":"deu","name":"German","nativeName":"Deutsch"}]}]', '[{"name":"Switzerland"},{"name":"Austria"}]');

          $this->countryService = new CountryService(['', 'Germany', 'Switzerland'], $this->client);
    }

    public function testGetResult()
    {

    	$result = $this->countryService->getResult();

 		$this->assertContains("do speak the same language", $result);
    }


}