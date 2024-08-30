<?php

namespace App\Exceptions\Custom;

use App\Exceptions\Custom\AbstractAPIException;

/**
 * Thrown when the application finds invalid data independent of a Laravel validator
 * 
 * @package App\Exceptions\Custom
 */
class InvalidDataException extends AbstractAPIException
{
    /**
     * Initialize the error
     * 
     * @param string $errorReason 
     * @return void 
     */
    public function __construct(
        protected string $errorReason = 'Invalid data given'
    ) {}

    /**
     * Return the set message
     * 
     * @return string 
     */
    public function getDetailedMessage(): string
    {
        return $this->errorReason;
    }

    /**
     * Return the set message
     * 
     * @return string 
     */
    public function getPrettyMessage(): string
    {
        return $this->errorReason;
    }

    /**
     * Get status code of exception
     * 
     * @return int 
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}