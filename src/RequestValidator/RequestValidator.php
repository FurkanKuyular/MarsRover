<?php

namespace App\RequestValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var RequestValidatorInterface
     */
    protected RequestValidatorInterface $validatorClass;

    /**
     * @var mixed
     */
    protected mixed $dataClass;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $validatorClass
     * @param $dataClass
     *
     * @throws \Exception
     */
    public function build(string $validatorClass, $dataClass): void
    {
        $this->validatorClass = new $validatorClass($this->validator);

        $this->validatorClass->setValidationExceptionMessage($this->getInvalidParameterMessage());

        if (!($this->validatorClass instanceof RequestValidatorInterface)) {
            throw new \Exception('$validatorClass must be instance of RequestValidatorInterface');
        }

        $this->dataClass = $dataClass;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws
     */
    public function handleRequest(Request $request): void
    {
        if (Request::METHOD_GET === $request->getMethod()) {
            $input = $request->query->all();
        } else {
            $input = $request->request->all();
        }

        $this->validatorClass->validate($input);

        $this->accept(array_keys($this->validatorClass->rules()->fields), $input);
    }

    /**
     * @param array $data
     * @param array|null $groups
     *
     * @throws
     */
    public function handleData(array $data, array $groups = null): void
    {
        $this->validatorClass->validate($data, $groups);

        $this->accept(array_keys($this->validatorClass->rules()->fields), $data);
    }

    /**
     * @param array $parameters
     * @param array $input
     *
     * @throws \ReflectionException
     */
    public function accept(array $parameters, array $input): void
    {
        foreach ($parameters as $parameter) {
            $reflection = new \ReflectionProperty($this->dataClass, $parameter);
            $reflection->setAccessible(true);
            $reflection->setValue($this->dataClass, $input[$parameter] ?? null);
        }
    }

    /**
     * @return string
     */
    public function getInvalidParameterMessage(): string
    {
        return 'Ge??ersiz Parametre Hatas??';
    }
}
