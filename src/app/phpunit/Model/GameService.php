<?php
use Model\GameService;

class GameServiceTest extends PHPUnit_Framework_TestCase
{
    public function testConvertToUnderScore()
    {
        $word = 'convertmetounderscore';

        $gameService = new GameService(new \Slim\Slim());
        $convertedWord = $gameService->convertToUnderScore($word);

        $this->assertEquals(strlen($word), strlen($convertedWord));
    }
}