<?php
namespace Model;

Class UserService
{

    function __construct($app)
    {
       $user =  $app->entityManager->getRepository('Model\Entity\User')->find(1);
        echo $user->getName();
    }
}