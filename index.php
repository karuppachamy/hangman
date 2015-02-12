<?php
require('/src/app/config/bootstrap.php');

use Model\GameService;
use Model\WordService;
use Model\ValidationService;
use Respect\Validation\Validator as v;

$app->response->headers->set('Content-Type', 'application/json');

$app->get('/game', function() use ($app) {
    $gameService = new GameService($app);
    $data = $gameService->getAllGames();
    
    return $app->response->setBody(json_encode($data));
});


$app->post('/game', function () use ($app) {
    $wordService = new WordService();
    $gameService = new GameService($app, $wordService);
    $game = $gameService->createNewGame();
    
    return $app->response->setBody(json_encode($game));
}
);

$app->put('/game/:id', function ($id) use ($app) {
    $validationService = new ValidationService();
    $inputData = $app->getInstance()->request()->getBody();
    $validationService->validateInput($inputData);
    if ($validationService->hasErrors()) {
        return $app->response->setBody(json_encode($validationService->getErrors()));
    }
    $gameService = new GameService($app);
    $gameData = json_decode($inputData);
    $game = $gameService->updateGame($id, $gameData);

        return $app->response->setBody(json_encode($game));
    }
);

$app->run();

