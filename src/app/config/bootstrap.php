<?php
require_once('vendor/autoload.php');
require_once('config.php');
$path = '/src/app/Model/Entity';

$entitiesPath  = array($path);
$config        = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($entitiesPath, true);
$entityManager = Doctrine\ORM\EntityManager::create($dbParams, $config);

$app  = new \Slim\Slim(array(
    'debug' => true
));
$app->entityManager = $entityManager;
