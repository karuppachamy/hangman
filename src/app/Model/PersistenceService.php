<?php
namespace Model;

class PersistenceService
{
    private $entityManager;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Save the given object into database.
     *
     * @param $object
     */
    public function save($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    /**
     * Find all the games available.
     *
     * @return array
     */
    public function findAllGames()
    {
        return $this->entityManager->getRepository('Model\Entity\Game')->findAll();
    }

    /**
     * Find a game based on given id.
     *
     * @param integer $id
     * @return Model\Entity\Game
     */
    public function findOneGameById($id)
    {
        return $this->entityManager->getRepository('Model\Entity\Game')->find($id);
    }
}
