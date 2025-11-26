<?php

namespace TokenManagement\SDK\Exceptions;

/**
 * Exception thrown when validation fails
 * 
 * @package TokenManagement\SDK\Exceptions
 */
class ValidationException extends ApiException
{
    /** @var array */
    private $validationErrors;

    /**
     * Create a new validation exception
     *
     * @param string $message
     * @param int $code
     * @param array $validationErrors
     */
    public function __construct(string $message, int $code = 0, array $validationErrors = [])
    {
        parent::__construct($message, $code);
        $this->validationErrors = $validationErrors;
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
