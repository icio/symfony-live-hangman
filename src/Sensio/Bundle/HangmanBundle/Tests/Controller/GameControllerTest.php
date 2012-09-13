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

    public function testTryInvalidWord()
    {
    	$crawler = $this->client->request('GET', '/game/');

    	$form = $crawler->selectButton('Let me guess...')->form();
    	$crawler = $this->client->submit($form, array('word' => 'no!'));

    	$this->assertNotEquals(
    			$crawler->filter('#content > h2:first-child')->text(),
    			'Congratulations!'
    		);
    }

    public function testGuessWord()
    {
    	$this->client->request('GET', '/game/');
    	$crawler = $this->playLetters('XHP');
    	$this->assertEquals('Congratulations!', $crawler->filter('#content > h2:first-child')->text());
    }

    public function testHanging()
    {
    	$this->client->request('GET', '/game/');
    	$crawler = $this->playLetters(str_repeat('Z', Game::MAX_ATTEMPTS));
    	$this->assertEquals(
    			'Game Over!',
    			$crawler->filter('#content > h2:first-child')->text()
    		);
    }

    private function playLetters($letters)
    {
    	foreach(str_split($letters) as $letter)
    		$crawler = $this->playLetter($letter);

    	return $crawler;
    }

    private function playLetter($letter)
    {
    	$crawler = $this->client->getCrawler();
    	$link = $crawler->selectLink($letter)->link();
    	return $this->client->click($link);
    }
}
