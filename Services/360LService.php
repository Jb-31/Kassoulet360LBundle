<?php
namespace Kassoulet\360LBundle\Services;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\MailerBundle\Entity\EventLogger;

class EventLoggerService
{
    private $em;
    private $api;
    
    /**
     * Constructor
     */
    public function __construct(EntityManager $entityManager) {
        //Init entityManager
        $this->em = $entityManager;
    }     
    
    /**
     * Set the 360Learning API SDK instance
     *
     * @param 360LClient $api
     * @return $this
     */
    public function setApi(360LearningClient $api)
    {
        $this->api = $api;
        return $this;
    }
    
    public function updateUsers(){
        
        
    }
    
    public function updateStages(){
        
        
    }
    
   
    
    /*
     * Une tache cron viendra récupérer la liste des utilisateurs de 360Learning, la comparer avec les utilisateurs existants sur eleo et ajouter les manquants dans 360Learning
     * Une autre tache cron viendra récupérer le catalogue 360Learning et ajouter les manquants dans le catalogue eleo
     */
    
    
    
    
}
