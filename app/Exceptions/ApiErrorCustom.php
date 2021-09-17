<?php

namespace App\Exceptions;

use Exception;

/**
 * Custom class to standardize the API responses, even passing an array in the Exception for more information.
 */
class ApiErrorCustom extends Exception
{
    private $options;

    public function __construct(string $message, int $code, Exception $previous = null, array $options = null)
    {
        parent::__construct($message, $code, $previous);

        $this->options = $options;
    }

    /**
     * Returns the array that was passed in the exception call options, building a standard message.
     * @return array|null
     */
    public function getOptions()
    {
        return $this->options;
    }
}
