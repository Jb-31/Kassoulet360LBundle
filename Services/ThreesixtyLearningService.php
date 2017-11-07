<?php
namespace Kassoulet\ThreesixtyLearningBundle\Services;

use Doctrine\ORM\EntityManager;
use Kassoulet\ThreesixtyLearningBundle\ThreesixtyLearning\ThreesixtyLearningClient as ThreesixtyLearningClient;

class ThreesixtyLearningService
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
     * Set the ThreesixtyLearning API SDK instance
     *
     * @param ThreesixtyLearningClient $api
     * @return $this
     */
    public function setApi(Kassoulet\ThreesixtyLearningBundle\ThreesixtyLearning\ThreesixtyLearningClient $api)
    {
        print_r('init api');
        $this->api = $api;
        return $this;
    }
    
    public function updateUsers(){
        
        //Récup liste utilisateurs 360Learning
        $ThreesixtyLearningUsers = $this->api->getUsers();
        
        //Création array des mails users
        $mailArrayThreesixtyLearning = array();
        foreach($ThreesixtyLearningUsers as $key => $value){            
            if($key == 'mail') $mailArrayThreesixtyLearning[]= $value;            
        }
        
        //Récup des nouveaux utilisateurs eleo (non présents sur 360Learning)        
        $eleoUsers =  $this->em->getRepository('OswUserBundle:User')->getNewUsersFilteredOnMail($mailArrayThreesixtyLearning);
        
        //On parcours les nouveaux utilisateurs et on les ajoute dans 360Learning (ou on les invite???)
        foreach($eleoUsers as $eleoUser){            
            $this->api->inviteUser($eleoUser->getEmail());
            //$this->api->createUser($eleoUser->getEmail(), $password, $firstName = NULL, $lastName = NULL, $phone = NULL, $lang = NULL, 'learner', $job = NULL, $organization = NULL,  NULL, NULL, $notify=false);            
        }
        
    }
    
    public function updateStages(){
        //Récup du catalogue des stages 360Learning
        $ThreesixtyLearningStages = $this->api->getCourses();        
        
        //Récup du catalogue eleo, filtré sur les stages importés de 360Learning
        //Ajout des deux colonnes sur l'entité stage : externalImportName = "360Learning" et externalImportID="58eb5c621a92bb4fb526b2b0"
        //Cette fonction retourne un array simple des externalImportID
        $eleoStages360 =  $this->em->getRepository('OswTrainingBundle:Training')->getTrainingsByExternalImportName('360Learning');
        
        //Boucle sur les stages 360Learning et ajout des stages qui ne sont pas déja importés        
        foreach($ThreesixtyLearningStages as $ThreesixtyLearningStage){ 
            
            if(!in_array($ThreesixtyLearningStage['_id'], $eleoStages360)){                
                
                $newTraining = new Training();
                $newTraining -> setExternalImportName('360Learning');
                $newTraining -> setExternalImportID($ThreesixtyLearningStage['_id']);
                $newTraining -> setName($ThreesixtyLearningStage['name']);
                
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
