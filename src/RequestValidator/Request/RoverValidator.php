<?php

namespace App\RequestValidator\Request;

use App\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class RoverValidator extends AbstractRequestValidator
{
    /**
     * @return Collection
     */
    public function rules(): Collection
    {
        return new Assert\Collection([
            'commands' => [
                new Assert\NotBlank(),
                new Assert\Regex('/[LMR\S]+$/'),
            ],
        ]);
    }
}