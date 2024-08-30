<?php

namespace App\Exceptions\Custom;

use Exception;

abstract class AbstractAPIException extends Exception
{
    /**
     * Determines if Laravel should report the error
     * 
     * @var bool
     */
    public bool $shouldBeReported = false;

    /**
     * Fill in the error message data to display if reported
     * 
     * @return void 
     */
    public function __construct()
    {
        $this->message = $this->getDetailedMessage();
    }

    /**
     * Get message Exception should return
     * 
     * @return string 
     */
    abstract public function getDetailedMessage(): string;
    
    /**
     * Get pretty message Exception should return, this value will be displayed to the end user
     * 
     * @return string 
     */
    public function getPrettyMessage(): string
    {
        return 'Sorry, something went wrong';
    }

    /**
     * Get status code of exception
     * 
     * @return int 
     */
    public function getStatusCode(): int
    {
        return 500;
    }
}