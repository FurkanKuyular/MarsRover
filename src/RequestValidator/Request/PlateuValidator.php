<?php

namespace App\RequestValidator\Request;

use App\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class PlateuValidator extends AbstractRequestValidator
{
    /**
     * @return Collection
     */
    public function rules(): Collection
    {
        return new Assert\Collection([
            'xCoordination' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'int']),
            ],
            'yCoordination' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'int']),
            ],
            'compassCharacter' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Length(1),
            ],
        ]);
    }
}