<?php
require('/src/app/config/bootstrap.php');

use Model\GameService;
use Model\WordService;

use Respect\Validation\Validator  as v;

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

        $gameService = new GameService($app);

        $gameData = json_decode($app->getInstance()->request()->getBody());

        $game = $gameService->updateGame($id, $gameData);

        return $app->response->setBody(json_encode($game));
    }
);
$app->run();

