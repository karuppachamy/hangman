<?php
require('/src/app/config/bootstrap.php');

use Model\GameService;
use Model\WordService;
use Model\ValidationService;
use Model\PersistenceService;
use Respect\Validation\Validator as v;

$app->response->headers->set('Content-Type', 'application/json');

$app->get('/game', function() use ($app, $entityManager) {
    $persistenceService = new PersistenceService($entityManager);
    $gameService = new GameService($persistenceService);
    $data = $gameService->getAllGames();
    
    return $app->response->setBody(json_encode($data));
});


$app->post('/game', function () use ($app, $entityManager) {
    $wordService = new WordService();
    $persistenceService = new PersistenceService($entityManager);
    $gameService = new GameService($persistenceService, $wordService);
    $game = $gameService->createNewGame();
    
    return $app->response->setBody(json_encode($game));
}
);

$app->put('/game/:id', function ($id) use ($app, $entityManager) {
    $validationService = new ValidationService();
    $inputData = $app->getInstance()->request()->getBody();
    $validationService->validateInput($inputData);
    if ($validationService->hasErrors()) {
        return $app->response->setBody(json_encode($validationService->getErrors()));
    }
    $persistenceService = new PersistenceService($entityManager);
    $gameService = new GameService($persistenceService);
    $gameData = json_decode($inputData);
    $game = $gameService->updateGame($id, $gameData);

        return $app->response->setBody(json_encode($game));
    }
);

$app->run();

