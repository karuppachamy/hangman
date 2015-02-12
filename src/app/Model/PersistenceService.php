<?php
namespace Model;

class PersistenceService 
{
    private $entityManager;
    
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
    
    public function save($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
    
    public function findAllGames()
    {
        return $this->entityManager->getRepository('Model\Entity\Game')->findAll();
    }
    
    public function findOneGameById($id)
    {
        return $this->entityManager->getRepository('Model\Entity\Game')->find($id);
    }
}
?>
