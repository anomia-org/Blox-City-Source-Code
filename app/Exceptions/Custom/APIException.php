<?php

namespace App\Exceptions\Custom;

use App\Exceptions\Custom\AbstractAPIException;

class APIException extends AbstractAPIException
{
    protected $errorMsg;

    /**
     * Initalize the general exception with a message
     * 
     * @param string $error 
     * @return void 
     */
    public function __construct($error = "Sorry, something went wrong")
    {
        $this->errorMsg = $error;
    }

    /**
     * Return the set message
     * 
     * @return string 
     */
    public function getDetailedMessage(): string
    {
        return $this->errorMsg;
    }

    /**
     * Return the set message
     * 
     * @return string 
     */
    public function getPrettyMessage(): string
    {
        return $this->errorMsg;
    }
}