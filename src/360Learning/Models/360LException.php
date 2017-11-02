<?php

namespace 360Learning\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class 360LException extends \Exception {
	var $message;
	var $httpStatusCode;
	var $360LearningApiErrorCode;
}

?>