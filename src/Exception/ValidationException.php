<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends AbstractApiException
{
    /**
     * Prepare error messages for invalid form requests
     *
     * @param string                                $message
     * @param ConstraintViolationListInterface|null $constraintViolationList
     */
    public function __construct(string $message, ?ConstraintViolationListInterface $constraintViolationList = null)
    {
        if ($constraintViolationList instanceof ConstraintViolationListInterface) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $constraintViolation */
            foreach ($constraintViolationList as $constraintViolation) {
                $this->addError(str_replace(['[', ']'], '', $constraintViolation->getPropertyPath()), $constraintViolation->getMessage());
            }
        }

        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }
}