<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
	{
		$exception = $event->getThrowable();

        if ($exception instanceof \Throwable) {
            if ($exception instanceof NotFoundHttpException) {
                $event->setResponse($this->getHttpNotFoundExceptionResponse($exception));
            } else {
                $event->setResponse($this->getThrowableExceptionResponse());
            }
        }
    }

    /**
     * @return JsonResponse
     */
    protected function getThrowableExceptionResponse(): JsonResponse
    {
        $content = [
            'success' => false,
            'message' => 'Teknik bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        ];

        return new JsonResponse($content, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param \Exception|null $exception
     *
     * @return JsonResponse
     */
    protected function getHttpNotFoundExceptionResponse(\Exception $exception = null): JsonResponse
    {
        $content = [
          'success' => false,
          'message' =>  $exception->getMessage() ?? 'Not found',
        ];

        return new JsonResponse($content, Response::HTTP_NOT_FOUND);
    }
}
