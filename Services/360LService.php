<?php
namespace Kassoulet\ThreesixtyLearningBundle\Services;

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
     * @param 360LearningClient $api
     * @return $this
     */
    public function setApi(ThreesixtyLearningClient $api)
    {
        $this->api = $api;
        return $this;
    }
    
    public function updateUsers(){
        
        //Récup liste utilisateurs 360Learning
        $360LearningUsers = $this->api->getUsers();
        
        //Création array des mails users
        $mailArray360L = array();
        foreach($360LearningUsers as $key => $value){            
            if($key == 'mail') $mailArray360L[]= $value;            
        }
        
        //Récup des nouveaux utilisateurs eleo (non présents sur 360Learning)        
        $eleoUsers =  $this->em->getRepository('OswUserBundle:User')->getNewUsersFilteredOnMail($mailArray360L);
        
        //On parcours les nouveaux utilisateurs et on les ajoute dans 360Learning (ou on les invite???)
        foreach($eleoUsers as $eleoUser){            
            $this->api->inviteUser($eleoUser->getEmail());
            //$this->api->createUser($eleoUser->getEmail(), $password, $firstName = NULL, $lastName = NULL, $phone = NULL, $lang = NULL, 'learner', $job = NULL, $organization = NULL,  NULL, NULL, $notify=false);            
        }
        
    }
    
    public function updateStages(){
        //Récup du catalogue des stages 360Learning
        $360LearningStages = $this->api->getCourses();        
        
        //Récup du catalogue eleo, filtré sur les stages importés de 360Learning
        //Ajout des deux colonnes sur l'entité stage : externalImportName = "360Learning" et externalImportID="58eb5c621a92bb4fb526b2b0"
        //Cette fonction retourne un array simple des externalImportID
        $eleoStages360 =  $this->em->getRepository('OswTrainingBundle:Training')->getTrainingsByExternalImportName('360Learning');
        
        //Boucle sur les stages 360Learning et ajout des stages qui ne sont pas déja importés        
        foreach($360LearningStages as $360LearningStage){ 
            
            if(!in_array($360LearningStage['_id'], $eleoStages360){                
                
                $newTraining = new Training();
                $newTraining -> setExternalImportName('360Learning');
                $newTraining -> setExternalImportID($360LearningStage['_id']);
                $newTraining -> setName($360LearningStage['name']);
                
                $this->em->persist($newTraining);
                $this->em->flush();
            } 
        }
        
    }
    
    public function updateStagesProgress(){
        
        //A définir comment on gère les status des demandes, si on gère un pourcentage dans l'appli, une date, etc...        
        
    }
       
    
    /*
     * Une tache cron viendra récupérer la liste des utilisateurs de 360Learning, la comparer avec les utilisateurs existants sur eleo et ajouter les manquants dans 360Learning
     * Une autre tache cron viendra récupérer le catalogue 360Learning et ajouter les manquants dans le catalogue eleo
     */
    
    
}
