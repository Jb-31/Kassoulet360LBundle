<?php
namespace Kassoulet\360LBundle\Services;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\MailerBundle\Entity\EventLogger;

class EventLoggerService
{
    private $em;
    
    /**
     * Constructor
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
        
    }
    
    public function addEvent($ref, $entities){
        
        $event = new EventLogger();
        
        $event->setDatetimeAdd(new \DateTime());
        $event->setRef($ref);
        $event->setEntitiesAffected($entities);
        $event->setStatut(0);
        
        $this->em->persist($event);
        $this->em->flush();
    }
    
    //including update of datatimeHandled & statut
    public function getEvents($ref){
        $events =  $this->em->getRepository('LexikMailerBundle:EventLogger')->getEventsByRef($ref);
        
        //update datetimeHandled & statut
        $this->em->getRepository('LexikMailerBundle:EventLogger')->HandleEvents($events);
        
        return $events;
        
    }
    
    // delete events whith status = 1;
    public function cleanEvents(){
        $this->em->getRepository('LexikMailerBundle:EventLogger')->deleteHandledEvents();
    }
    
    
}
