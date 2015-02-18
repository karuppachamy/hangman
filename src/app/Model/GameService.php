<?php
namespace Model;

use Model\Entity\Game;
use Model\WordService;
use Model\PersistenceService;

class GameService
{
    private $wordService;
    private $persistenceService;
    const CHARACTER_MASK = '-';
    const INITIAL_TRY_VALUE = 0;
    const INCREMENT_TRY_VALUE = 1;

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
     * @param integer $id
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
        $game->setTries(self::INITIAL_TRY_VALUE);
        $game->setStatus(self::INITIAL_TRY_VALUE);
        
        return $game;
    }
    
    /**
     * Get all the available games.
     *
     * @return array $games
     */
    public function getAllGames()
    {
        $games = $this->persistenceService->findAllGames();
        
        return $games;
    }

    /**
     * Update the game information.
     *
     * @param integer $id
     * @param array $gameData
     *
     * @return \Model\Entity\Game Game
     */
    public function updateGame($id, $gameData)
    {
        $game = $this->prepareGameObject($id);
        
        $this->processGuess($game, $gameData);
        $this->persistenceService->save($game);

        return $game;
    }

    /**
     * Convert all the characters to under score.
     *
     * @param string $word
     *
     * @return string
     */
    public function convertToUnderScore($word)
    {
        return str_repeat(self::CHARACTER_MASK, strlen($word));
    }

    /**
     * Process the guessed characters and update accordingly.
     *
     * @param \Model\Entity\Game $game
     * @param Object $gameData
     */
    private function processGuess(Game $game, $gameData)
    {
        $originalWord = str_split($game->getWord());
        $guessCharacter = array($gameData->char);
        $guessedCharacters = $this->checkGuess($originalWord, $guessCharacter);
        
        if (count($guessedCharacters) > 0) {
            $processedWord =  $this->replaceUnderScoreWithCharacter($guessedCharacters, $game);
            $game->setGuessWord($processedWord);
        } else {
            $game->setTries(self::INCREMENT_TRY_VALUE);
        }
        
        $this->updateGameStatus($game);
    }

    /**
     * Update the status of the game.
     *
     * @param Game $game
     */
    private function updateGameStatus(Game $game)
    {
        if ($game->isSuccess()) {
            $game->setStatus(Game::GAME_STATUS_SUCCESS);
        }
        
        if ($game->isFailure()) {
            $game->setStatus(Game::GAME_STATUS_FAILURE);
        }
    }

    /**
     * Check the guess is correct and return the correctly guessed characters.
     *
     * @param array $originalWord
     * @param array $guessCharacter
     *
     * @return array
     */
    private function checkGuess(array $originalWord, array $guessCharacter)
    {
        $guessedCharacters = array_intersect($originalWord, $guessCharacter);

        return $guessedCharacters;
    }

    /**
     * Based on the guessed character update the underscore string.
     *
     * @param string $guessedCharacters
     * @param \Model\Entity\Game $game
     *
     * @return string
     */
    private function replaceUnderScoreWithCharacter($guessedCharacters, Game $game)
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
