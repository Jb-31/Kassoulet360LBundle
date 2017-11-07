<?php

namespace ThreesixtyLearning\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class ThreesixtyLearningException extends \Exception {
	var $message;
	var $httpStatusCode;
	var $ThreesixtyLearningApiErrorCode;
}

?>