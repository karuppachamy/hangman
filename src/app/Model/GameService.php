<?php
namespace Model;

use Model\Entity\Game;
Class GameService
{

    private $entityManager;
    private $wordService;
    private $persistenceService;

    /**
     * @param \Slim\Slim $app
     * @param \Model\WordService|null $wordService
     */
    function __construct($persistenceService, \Model\WordService $wordService = null)
    {
        $this->wordService = $wordService;
        $this->persistenceService = $persistenceService;
    }

    public function createNewGame()
    {
        $game = new Game();
        $game->setWord($this->wordService->getRandomWord());
        $game->setGuessWord($this->convertToUnderScore($game->getWord()));
        $game->setTries(0);
        $game->setStatus(0);
        $this->persistenceService->save($game);

        return $game->toArray();
    }

    public function getAllGames()
    {
        $games = $this->persistenceService->findAllGames();

        $data = array_map(
            function ($game)
            {
               return $game->toArray();
            }, $games);

        return $data;
    }
    public function updateGame($id, $gameData)
    {
        $game = $this->persistenceService->findOneGameById($id);
        $this->processGuess($game, $gameData);
        $this->persistenceService->save($game);

        return $game->toArray();
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
        
        if ($game->isFailure()){
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
            if (array_key_exists($key, $guessedCharacters)){
                $value = $guessedCharacters[$key];
            }
        });

        return (implode('', $guessedWord));
    }
}