<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class DocumentController extends AbstractController
{
    /**
     * @param Profiler|null $profiler
     *
     * @return Response
     */
    public function index(?Profiler $profiler): Response
    {
        if (null !== $profiler) {
            $profiler->disable();
        }

        return $this->render('@doc/index.html');
    }

    /**
     * @return Response
     */
    public function config(): Response
    {
        return $this->json($this->renderView('@doc/swagger_config.json'));
    }
}
