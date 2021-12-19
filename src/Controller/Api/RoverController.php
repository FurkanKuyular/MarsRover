<?php

namespace App\Controller\Api;

use App\DTO\RoverDTO;
use App\Service\RoverService;
use App\Service\PlateuService;
use App\Controller\AbstractController;
use App\RequestValidator\RequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\RequestValidator\Request\RoverValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoverController extends AbstractController
{
    /**
     * @Route("/rover", methods={"PUT"}, name="api.rover.upsert")
     *
     * @param Request          $request
     * @param RequestValidator $requestValidator
     * @param RoverService     $roverService
     * @param PlateuService    $plateuService
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function upsertRover(Request $request, RequestValidator $requestValidator, RoverService $roverService, PlateuService $plateuService): JsonResponse
    {
        $roverDTO = new RoverDTO();

        $requestValidator->build(RoverValidator::class, $roverDTO);
        $requestValidator->handleData((array) json_decode($request->getContent(), true));

        $roverDTO->setCommands(str_replace(' ', '', $roverDTO->getCommands()));

        $plateu = $plateuService->getPlateu();

        if (empty($plateu)) {
            throw new NotFoundHttpException('Öncelikle bir plato oluşturmanız gerekmektedir.');
        }

        $rover = $roverService->upsertRover($plateu, $roverDTO);

        if (is_null($rover)) {
            throw new \Exception('Rover could not upsert');
        }

        return $this->json([
           'rover' => $rover,
        ]);
    }

    /**
     * @Route("/rover", methods={"GET"}, name="api.rover.get")
     *
     * @param RoverService $roverService
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function getRover(RoverService $roverService): JsonResponse
    {
        $rover = $roverService->getRover();

        if (empty($rover)) {
            throw new NotFoundHttpException('Rover could not found');
        }

        return $this->json([
           'rover' => $rover,
        ]);
    }
}