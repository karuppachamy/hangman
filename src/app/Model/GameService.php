<?php
namespace Model;

use Model\Entity\Game;
use Model\WordService;
use Model\PersistenceService;
class GameService
{
    private $wordService;
    private $persistenceService;

    /**
     * 
     * @param \Model\PersistenceService $persistenceService
     * @param \Model\WordService $wordService
     */
    public function __construct(PersistenceService $persistenceService, WordService $wordService = null)
    {
        $this->wordService = $wordService;
        $this->persistenceService = $persistenceService;
    }

    /**
     * Create a new game.
     * 
     * @return Model\Entity\Game
     */
    public function createNewGame()
    {
        $game = $this->prepareGameObject();
        $this->persistenceService->save($game);

        return $game;
    }

    /**
     * Prepare the New/Existing based on the inputs.
     * 
     * @param type $id
     * 
     * @return \Model\Entity\Game
     */
    private function prepareGameObject($id = null)
    {
        if ($id) {
            return $this->persistenceService->findOneGameById($id);
        }
        
        $game = new Game();
        $game->setWord($this->wordService->getRandomWord());
        $game->setGuessWord($this->convertToUnderScore($game->getWord()));
        $game->setTries(0);
        $game->setStatus(0);
        
        return $game;
    }
    
    /**
     * Get all the available games.
     * 
     * @return type array
     */
    public function getAllGames()
    {
        $games = $this->persistenceService->findAllGames();
        
        return $games;
    }
    
    public function updateGame($id, $gameData)
    {
        $game = $this->prepareGameObject($id);
        
        $this->processGuess($game, $gameData);
        $this->persistenceService->save($game);

        return $game;
    }

    public function convertToUnderScore($word)
    {
        return str_repeat('-', strlen($word));
    }

    private function processGuess($game, $gameData)
    {
        $originalWord = str_split($game->getWord());
        $guessCharacter = array($gameData->char);
        $guessedCharacters = $this->checkGuess($originalWord, $guessCharacter);
        
        if (count($guessedCharacters) > 0) {
            $processedWord =  $this->replaceUnderScoreWithCharacter($guessedCharacters, $game);
            $game->setGuessWord($processedWord);
        } else {
            $game->setTries(1);
        }
        
        $this->updateGameStatus($game);
    }
    
    private function updateGameStatus($game)
    {
        if ($game->isSuccess()) {
            $game->setStatus(Game::GAME_STATUS_SUCCESS);
        }
        
        if ($game->isFailure()) {
            $game->setStatus(Game::GAME_STATUS_FAILURE);
        }
    }

    private function checkGuess(array $originalWord, array $guessCharacter)
    {
        $guessedCharacters = array_intersect($originalWord, $guessCharacter);

        return $guessedCharacters;
    }

    private function replaceUnderScoreWithCharacter($guessedCharacters, $game)
    {
        $guessedWord = str_split($game->getGuessWord());
        array_walk($guessedWord, function (&$value, $key) use ($guessedCharacters) {
            if (array_key_exists($key, $guessedCharacters)) {
                $value = $guessedCharacters[$key];
            }
        });
        return (implode('', $guessedWord));
    }
}
