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
    function getUserCourseStats($courseID = NULL, $userEmail = NULL) {        
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
        _id: group’s id    
        name: group’s name    
        public: group’s privacy    
        users: array of the learners of the group    
        coaches : array of the coaches of the group    
        custom : group's additionnal info
     */
    function getGroup($groupID = NULL) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/groups/$groupID'));
    }  
   
    /**
     * Delete a group.
     *
     * Returns : 
        - In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
        - In case of error, an error code.
     */
    function deleteGroup($groupID = NULL) {
        return new DynamicResponseModel($this->processRestRequest('DELETE', '/groups/$groupID'));
    }   
    
    /**
     * Add a user to a given group.    
     */
    function addUserToGroup($groupID = NULL, $userEmail = NULL) {
        return new DynamicResponseModel($this->processRestRequest('PUT', '/groups/$groupID/users/$userEmail'));
    }
    
    /**
     * Remove a user from a given group.
     *
     * Returns :
        - In case of success, an empty response code 204 (No Content) in accordance with RFC 7231.
        - In case of error, an error code.
     */
    function deleteUserFromGroup($groupID = NULL, $userEmail = NULL) {
        return new DynamicResponseModel($this->processRestRequest('DELETE', '/groups/$groupID/users/$userEmail'));
    }     
  
    /**
     * Create a group.
     *
     * Returns :
        - A status code (group_created, missing argument : {name/public}, invalid argument : {name/public})
        - The group's id if successful
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
        A status code (group_updated, invalid argument : {name/public}) 
     */
    function updateGroup($groupID = NULL, $name = NULL, $public = NULL, $custom = NULL) {
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
    function getGroupPrograms($groupID = NULL) {
        return new DynamicResponseModel($this->processRestRequest('GET', '/groups/$groupID/programs'));
    }  
    
    
    
    
      
    /**********************************************************************************************************************************
     * Programs
     */
    
    GET
    getPrograms
    GET
    getProgram
    PUT
    addGroupToProgram
    PUT
    addUserToProgram
    DEL
    deleteUserFromProgram
    DEL
    deleteGroupFromProgram
    GET
    getUserProgramStats
    
    /**********************************************************************************************************************************
     * Users
     */
    
    GET
    getUsers
    POST
    inviteUser
    POST
    createUser with password
    GET
    getUser
    PUT
    updateUser
    DEL
    deleteUser
    PUT
    addManagerToUser
    DEL
    deleteManagerFromUser
    GET
    getUserPrograms
    GET
    getUserCourses
    
    
    
    
    
}

?>
