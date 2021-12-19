<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * {@inheritdoc}
     */
    protected function json($data, int $status = 200, array $headers = array(), array $context = array()): JsonResponse
    {
        $content = [
            'success' => true,
            'data' => $data,
        ];

        return (new JsonResponse())->setData($content)->setCharset('UTF-8')->setStatusCode($status);
    }
}