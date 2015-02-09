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

    protected static function getMethod($className, $methodName) {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }

    public function testCheckGuess() {
        $wordArray = array('h', 'e', 'l', 'l', 'o');
        $guessedCharacter = array('l');
        $checkGuessMethod = self::getMethod('Model\GameService', 'checkGuess');
        $gameService = new Model\GameService(new \Slim\Slim());
        $guessMatch =  $checkGuessMethod->invokeArgs($gameService, array($wordArray, $guessedCharacter));
        $this->assertCount(2, $guessMatch);
    }

    public function testReplaceUnderScoreWithCharacter()
    {
        $guessedCharacters = array(0 => 'i',4 => 'm');
        $game = new Model\Entity\Game();
        $game->setGuessWord('------');
        $replaceUnderScoreWithCharacterMethod = self::getMethod('Model\GameService', 'replaceUnderScoreWithCharacter');
        $gameService = new Model\GameService(new \Slim\Slim());
        $replaceWord =  $replaceUnderScoreWithCharacterMethod->invokeArgs($gameService, array($guessedCharacters, $game));
        $this->assertStringStartsWith('i', $replaceWord);

    }
}