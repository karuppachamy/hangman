<?php
require_once 'vendor/autoload.php';

use Model\Entity\Game;

class GameTest extends PHPUnit_Framework_TestCase
{
  public function testIsSuccess()
  {
      $game = new Game();
      $game->setWord('poo');
      $game->setGuessWord('poo');
      $this->assertTrue($game->isSuccess());
      
  }
  
  public function testIsFailure()
  {
      $game = new Game();
      
      $game->setTries(11);
      $this->assertTrue($game->isFailure());
      
  }  
}
?>
