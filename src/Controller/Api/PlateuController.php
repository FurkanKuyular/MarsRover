<?php

namespace App\Controller\Api;

use App\DTO\PlateuDTO;
use App\Service\PlateuService;
use App\Controller\AbstractController;
use App\RequestValidator\RequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\RequestValidator\Request\PlateuValidator;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlateuController extends AbstractController
{
    /**
     * @Route("/plateu", methods={"POST"}, name="api.plateu.create.coordinate")
     *
     * @param Request          $request
     * @param RequestValidator $requestValidator
     * @param PlateuService    $plateuService
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function createPlateu(Request $request, RequestValidator $requestValidator, PlateuService $plateuService): JsonResponse
    {
        $plateuDTO = new PlateuDTO();

        $requestValidator->build(PlateuValidator::class, $plateuDTO);
        $requestValidator->handleData((array) json_decode($request->getContent(), true));

        $plateu = $plateuService->createPlateu($plateuDTO);

        if (empty($plateu)) {
            throw new \Exception('Plateu could not created');
        }

        return $this->json([
            'plateu' => $plateu,
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/plateu", methods={"GET"}, name="api.plateu.get.coordinate")
     *
     * @param PlateuService $plateuService
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function getPlateu(PlateuService $plateuService): JsonResponse
    {
        $plateu = $plateuService->getPlateu();

        if (is_null($plateu)) {
            throw new \Exception('Plateu could not get');
        }

        return $this->json([
            'plateu' => $plateu
        ]);
    }
}