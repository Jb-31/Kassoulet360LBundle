<?php

namespace 360Learning;

use 360Learning\Models\DynamicResponseModel as DynamicResponseModel;
use 360Learning\360LearningClientBase as 360LearningClientBase;


class 360LearningClient extends 360LearningClientBase {
    
    private $server_token = NULL;
    
    /**
     * Create a new 360LearningClient.
     *
     * @param string $company : Your company id (company)
     * @param string $apiKey : Your API key (apiKey)
     * @param integer $timeout The timeout, in seconds to wait for an API call to complete before throwing an Exception.
     */
    function __construct($serverToken, $timeout = 30) {
        parent::__construct($serverToken, 'X-Postmark-Server-Token', $timeout);
    }   
    
    
    
    
           
    /**********************************************************************************************************************************
     * Courses
     */    
    
    /**
     * Retrieve the list of all your courses.
     * Returns : An array of courses (_id, name, author)     
     */
    function getCourses() {
        return new DynamicResponseModel($this->processRestRequest('GET', '/courses'));
    }    
    
    /**
     * Retrieve the statistics of a user for a given course.
     * Returns : An array of the user’s statistics by attempt (globalTime, progress, score, completedAt)     
     */
    function getUserCourseStats($courseID, $userEmail) {        
        return new DynamicResponseModel($this->processRestRequest('GET', '/courses/$courseID/stats/$userEmail'));
    }        
    
    
    
     
    
    /**********************************************************************************************************************************
     * Groups
     */
        
    /**
     * Retrieve the list of all your groups.
     * Returns : An array of groups (_id, name, public, custom)     
     */
    function getGroups() {
        return new DynamicResponseModel($this->processRestRequest('GET', '/groups'));
    }      
        
    /**
     * Get information about a given group.
     * Returns : 
     *  _id: group’s id    
     *  name: group’s name    
     *  public: group’s privacy    
     *  users: array of the learners of the group    
     *  coaches : array of the coaches of the group    
     *  custom : group's additionnal info
     */
    function getGroup($groupID) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/groups/$groupID'));
    }  
   
    /**
     * Delete a group.
     *
     * Returns : 
     *  - In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
     *  - In case of error, an error code.
     */
    function deleteGroup($groupID) {
        return new DynamicResponseModel($this->processRestRequest('DELETE', '/groups/$groupID'));
    }   
    
    /**
     * Add a user to a given group.    
     */
    function addUserToGroup($groupID, $userEmail) {
        return new DynamicResponseModel($this->processRestRequest('PUT', '/groups/$groupID/users/$userEmail'));
    }
    
    /**
     * Remove a user from a given group.
     *
     * Returns :
     *  - In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
     *  - In case of error, an error code.
     */
    function deleteUserFromGroup($groupID, $userEmail) {
        return new DynamicResponseModel($this->processRestRequest('DELETE', '/groups/$groupID/users/$userEmail'));
    }     
  
    /**
     * Create a group.
     *
     * Returns :
     *  - A status code (group_created, missing argument : {name/public}, invalid argument : {name/public})
     *  - The group's id if successful
     */
    function createGroup() {
        return new DynamicResponseModel($this->processRestRequest('POST', '/groups'));
    }    
  
    /**
     * Update a group's parameters.
     * 
     * @param string name : MyAPIUpdatedGroup - group's name (optionnal)
     * @param bool public :  false - group's privacy (optionnal)
     * @param string custom : A new custom info - group's additionnal information (optionnal)
     * 
     * Returns :
     *  A status code (group_updated, invalid argument : {name/public}) 
     */
    function updateGroup($groupID, $name = NULL, $public = NULL, $custom = NULL) {
        $body = array();
        $body["name"] = $matchName;
        $body["public"] = $trackOpens;
        $body["custom"] = $trackOpens;
        return new DynamicResponseModel($this->processRestRequest('PUT', '/groups/$groupID', $body));
    }       
    
    /**
     * Returns the program sessions to which the group has been invited.
     * "Will be available around october 17'."
     * Please note that the property programDuration returned is the estimated duration (in hours) of the program, optionnaly provided in the advanced options of the web interface.
     */
    function getGroupPrograms($groupID) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/groups/$groupID/programs'));
    }  
    
    
    
    
      
    /**********************************************************************************************************************************
     * Programs
     */    
    
    /**
     * Retrieve the list of all your programs.
     * Returns: An array of programs (_id, name, author, referents, startDate, endDate, programDuration, modules, users)
     *      _id : program id
     *      name: program name
     *      author: program author’s mail
     *      tutors: array of the referents’ mails
     *      startDate: program start date
     *      endDate: program end date
     *      programDuration: estimated duration of the program
     *      modules: array of the program modules. A module can be a course, a webinar or a classroom.
     *      users: array of the users enrolled in the program
     */
    function getPrograms() {
        return new DynamicResponseModel($this->processRestRequest('GET', '/programs'));
    }      
    
    /**
     * Get information about a given program.
     * Returns: An array of programs (_id, name, author, referents, startDate, endDate, programDuration, modules, users)
     *      _id : program id
     *      name: program name
     *      author: program author’s mail
     *      tutors: array of the referents’ mails
     *      startDate: program start date
     *      endDate: program end date
     *      programDuration: estimated duration of the program
     *      modules: array of the program modules. A module can be a course, a webinar or a classroom.
     *      users: array of the users enrolled in the program
     */
    function getProgram($programID) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/programs/$programID'));
    }     
    
    /**
     * Invite a group to a given program.    
     */
    function addGroupToProgram($programID, $groupID) {
        return new DynamicResponseModel($this->processRestRequest('PUT', '/programs/$programID/groups/$groupID'));
    }
    
    /**
     * Invite a user to a given program.
     */
    function addUserToProgram($programID, $userEmail) {
        return new DynamicResponseModel($this->processRestRequest('PUT', '/programs/$programID/users/$userEmail'));
    }
  
    /**
     * Uninvite a user from a given program.
     * Returns :
     *    In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
     *    In case of error, an error code.
     */
    function deleteUserFromProgram($programID, $userEmail) {
        return new DynamicResponseModel($this->processRestRequest('DEL', '/programs/$programID/users/$userEmail'));
    }
    
    /**
     * Uninvite a group from a given program.
     * Returns :
     *    In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
     *    In case of error, an error code.
     */
    function deleteGroupFromProgram($programID, $groupID) {
        return new DynamicResponseModel($this->processRestRequest('DEL', '/programs/$programID/groups/$$groupID'));
    }    
   
    /**
     * Retrieve the statistics of a user for a given program.
     * Returns:
     *    globalTime: total time spent by the user following the given program (milliseconds)
     *    progress: the learner’s progress status (%)
     *    score: the global score obtained by the user in the program (%)
     */
    function getUserProgramStats($programID, $userEmail) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/programs/$programID/stats/$userEmail'));
    }     
    
    
    
        
    
    /**********************************************************************************************************************************
     * Users
     */  
    
    /**
     * Retrieve the list of all your users.
     * Returns:
     *    An array of users (_id, mail, firstName, lastName, custom)
     */
    function getUsers() {
        return new DynamicResponseModel($this->processRestRequest('GET', '/users'));
    }
    
    /**
     * Send an invitation to a given user (who will receive an automatic invitation email). Only learners can be invited this way.
     *Parameters description (body) :
     *   mail : user’s mail
     * Returns :
     *  - A status code (invitation_created, invitation_already_exists)     
     */
    function inviteUser($userEmail) {
        $body = array();
        $body["mail"] = $userEmail;
        return new DynamicResponseModel($this->processRestRequest('POST', '/users',$body));
    }    
  
    /**
     * Create user
     * If the CREATE call deals with an email address which previously existed but was deactivated (cf. DELETE USER), the corresponding user is reactivated and the fields sent in the create are not taken into account.     
     * Parameters description (body) :    
     *  mail : user’s mail    
     *  password : user’s password (plaintext)    
     *  firstName : user’s first name (optional)    
     *  lastName : user’s last name (optional)    
     *  phone : user’s phone (optional)    
     *  lang : user’s default language (en, fr, es, de, it, pt, nl, cn, jp or kr) (optional)    
     *  role : learner, author or admin. This parameter is optional and set by default to learner.    
     *  job: user’s Title/Employment (optional)    
     *  organization: user’s organization (optional)    
     *  custom: user’s additional information (optional)    
     *  keywords[0]: label added with the API 0 (optional)    
     *  keywords[1]: label added with the API 1 (optional)    
     *  keywords[n]: label added with the API n (optional)    
     *  notify: if the login and the password are sent to the user. This parameter is optional and set by default to false.
     * Returns :
     *  A status code (user_created, email_already_used)
    */    
    function createUser($userEmail, $password, $firstName = NULL, $lastName = NULL, $phone = NULL, $lang = NULL, $role = 'learner', $job = NULL, $organization = NULL, $custom = NULL, $keywords = NULL, $notify=false) {
        $body = array();
        $body["mail"] = $userEmail;
        $body["password"] = $password;
        $body["firstName"] = $firstName;
        $body["lastName"] = $lastName;
        $body["phone"] = $phone;
        $body["lang"] = $lang;
        $body["role"] = $role;
        $body["job"] = $job;
        $body["organization"] = $organization;
        $body["custom"] = $custom;
        $body["keywords"] = $keywords;
        $body["notify"] = $notify;
        return new DynamicResponseModel($this->processRestRequest('POST', '/users',$body));
    }   
    
    /**
     * Retrieve basic information about a given user.
     * Returns :
     *    _id: user’s id
     *    mail: user’s mail
     *    firstName: user’s first name
     *    lastName: user’s first name
     *    custom: user’s additional information
     *    managers: user’s list of managers
     *    keywords: user’s list of labels
     *    groups : user's list of groups
     */
    function getUser($userEmail) {        
        return new DynamicResponseModel($this->processRestRequest('GET', '/users/$userEmail'));
    }
    
    /**
     * Update a user. The new keyword list replaces the existing list.
     * 
     *  BODY        
     *     role :  user - learner, author or admin. This parameter is optional and set by default to learner.
     *     custom :  updated custom field - user’s additional information (optional)
     *     keywords[]  
     *     mail : may be a new mail
     *
     * Returns :
     *  A status code ({}, mail_already_invited)
     */
    function updateUser($userEmail, $role, $custom, $keyword, $mail) {
        $body = array();
        $body["role"] = $role;
        $body["custom"] = $custom;
        $body["keywords"] = $keyword;
        $body["mail"] = $mail;
        return new DynamicResponseModel($this->processRestRequest('PUT', '/users/$userEmail', $body));
    }  
   
    /**
     * Deactivate a given user.
     * Please note that this call does not remove the data from the database but only disables the user: the user can no longer login and is no longer counted in the license number (if user licenses apply to your contrat). This behaviour is needed for coherent statistics of training resources in the dashboard.
     * If you create this user again (reactivation), the user will keep his programs, managers, managed users, learning statistics, labels, custom field and password but will lose his groups.
     * Warning: From the next release (postponed to September 2017), the user will lose his managers and managed users.
     * Returns :
     *     In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
     *     In case of error, an error code.
     */
    function deleteUser($userEmail) {
        return new DynamicResponseModel($this->processRestRequest('DEL', '/users/$userEmail'));
    }    
    
    /*
     * Assign a manager to a given user.     
     */    
    function addManagerToUser($userEmail, $managerEmail) {
        return new DynamicResponseModel($this->processRestRequest('PUT', '/users/$userEmail/managers/$managerEmail'));
    }    
   
    /*
     * Unassign a manager from a given user.
     * Returns :
     *    In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
     *    In case of error, an error code.
     */
    function addManagerToUser($userEmail, $managerEmail) {
        return new DynamicResponseModel($this->processRestRequest('DEL', '/users/$userEmail/managers/$managerEmail'));
    } 
    
    /**
     * Returns all the programs which have been assigned to the user (individually, via a group, or via open registration).     
     */
    function getUserPrograms($userEmailOrID) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/users/$userEmailOrID/programs'));
    }
    
    /**
     * Returns all the courses attempted by the user, with details for every attempt. 
     */
    function getUserCourses($userEmailOrID) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/users/$userEmailOrID/courses'));
    }      
    
}

?>
