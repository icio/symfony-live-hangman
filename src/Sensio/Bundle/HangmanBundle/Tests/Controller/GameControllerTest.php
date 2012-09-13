<?php

namespace Sensio\Bundle\HangmanBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Sensio\Bundle\HangmanBundle\Game\Game;

class GameControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
    	$this->client = static::createClient();
    	$this->client->followRedirects(true);
    }

    public function tearDown()
    {
    	$this->client = null;
    }
    
    public function testTryWord()
    {
    	// Submit the form on /game with guess "php"
    	$crawler = $this->client->request('GET', '/game/');
    	$form = $crawler->selectButton('Let me guess...')->form();
    	$crawler = $this->client->submit($form, array('word' => 'php'));

    	// Check for a congratulatory message in the response
    	$this->assertEquals(
    			$crawler->filter('#content > h2:first-child')->text(),
    			'Congratulations!'
    		);
    }
}
