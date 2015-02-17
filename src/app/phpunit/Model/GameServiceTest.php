<?php
require_once 'vendor/autoload.php';

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
    
    public function testCreateNewGame()
    {
        $wordServiceMock = $this->getMockBuilder('Model\WordService')
                     ->getMock();
        $wordServiceMock->method('getRandomWord')
             ->willReturn('abcd');

        $persistencServiceMock = $this->getMockBuilder('Model\PersistenceService')
                     ->disableOriginalConstructor()
                     ->getMock();
        $wordServiceMock->method('save')
             ->willReturn('true');   
        
        $gameService = new Model\GameService($persistencServiceMock, $wordServiceMock);
        
        $game = $gameService->createNewGame();
        $this->assertInstanceOf('Model\Entity\Game', $game);
    }
    
    public function testprocessGuessWithCorrectGuess()
    {
        $wordServiceMock = $this->getMockBuilder('Model\WordService')
                     ->getMock();
        $wordServiceMock->method('getRandomWord')
             ->willReturn('abcd');

        $persistencServiceMock = $this->getMockBuilder('Model\PersistenceService')
                     ->disableOriginalConstructor()
                     ->getMock();
        $wordServiceMock->method('save')
             ->willReturn('true'); 
        
        $processGuessMethod = self::getMethod('Model\GameService', 'processGuess');
        $gameService = new Model\GameService($persistencServiceMock, $wordServiceMock);
        
        $game = new Model\Entity\Game;
        $game->setWord('minions');
        $game->setGuessWord('-------');
        $game->setTries(0);
        $json = '{"char":"n"}';
        $gameData = json_decode($json);
        
        $processGuessMethod->invokeArgs($gameService, array($game, $gameData));
        $this->assertEquals(0, $game->getTries());
    }
    
    public function testprocessGuessWithWrongGuess()
    {
        $wordServiceMock = $this->getMockBuilder('Model\WordService')
                     ->getMock();
        $wordServiceMock->method('getRandomWord')
             ->willReturn('abcd');

        $persistencServiceMock = $this->getMockBuilder('Model\PersistenceService')
                     ->disableOriginalConstructor()
                     ->getMock();
        $wordServiceMock->method('save')
             ->willReturn('true'); 
        
        $processGuessMethod = self::getMethod('Model\GameService', 'processGuess');
        $gameService = new Model\GameService($persistencServiceMock, $wordServiceMock);
        
        $game = new Model\Entity\Game;
        $game->setWord('minions');
        $game->setGuessWord('-------');
        $game->setTries(0);
        $json = '{"char":"z"}';
        $gameData = json_decode($json);
        
        $processGuessMethod->invokeArgs($gameService, array($game, $gameData));
        $this->assertEquals(1, $game->getTries());
    } 
    
    public function testUpdateGame()
    {
        
    }
    
}