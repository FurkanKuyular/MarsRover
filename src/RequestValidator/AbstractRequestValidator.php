<?php

namespace App\RequestValidator;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractRequestValidator implements RequestValidatorInterface
{
    protected $validator;

    protected $validationExceptionMessage = 'Invalid Parameters';

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $input
     * @param array|null $groups
     *
     * @throws \App\Exception\ValidationException
     */
    public function validate($input, array $groups = null)
    {
        $violations = $this->validator->validate($input, $this->rules(), $groups);

        $this->violation($violations);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     *
     * @throws \App\Exception\ValidationException
     */
    public function violation(ConstraintViolationListInterface $constraintViolationList)
    {
        if ($constraintViolationList->count() > 0) {
            throw new ValidationException($this->getValidationExceptionMessage(), $constraintViolationList);
        }
    }

    /**
     * Get validation exception message.
     *
     * @return string
     */
    public function getValidationExceptionMessage(): string
    {
        return $this->validationExceptionMessage;
    }

    /**
     * Set validation exception message.
     *
     * @param string $validationExceptionMessage
     *
     * @return string
     */
    public function setValidationExceptionMessage(string $validationExceptionMessage): string
    {
        return $this->validationExceptionMessage = $validationExceptionMessage;
    }
}
