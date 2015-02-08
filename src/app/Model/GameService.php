<?php
namespace Model;

Class GameService
{

    private $entityManager;
    private $wordService;

    /**
     * @param $app
     * @param null $wordService
     */
    function __construct($app, $wordService = null)
    {
        $this->entityManager = $app->entityManager;
        $this->wordService = $wordService;
    }

    public function createNewGame()
    {
        $game = new \Model\Entity\Game();
        $game->setWord($this->wordService->getRandomWord());
        $game->setGuessWord($this->convertToUnderScore($game->getWord()));
        $game->setTries(0);
        $this->save($game);

        return $game->toArray();
    }

    public function updateGame($id, $gameData)
    {
        $game = $this->entityManager->getRepository('Model\Entity\Game')->find($id);
        $this->processGuess($game, $gameData);
        $this->save($game);

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

    private function save($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}