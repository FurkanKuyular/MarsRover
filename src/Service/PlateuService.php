<?php

namespace App\Service;

use App\DTO\PlateuDTO;
use App\Util\RedisUtil;

class PlateuService extends AbstractService
{
    /**
     * @param PlateuDTO $plateuDTO
     *
     * @return array|null
     */
    public function createPlateu(PlateuDTO $plateuDTO): ?array
    {
        try {
            $plateu = [
                'xCoordination' => $plateuDTO->getXCoordination(),
                'yCoordination' => $plateuDTO->getYCoordination(),
                'compassCharacter' => $plateuDTO->getCompassCharacter(),
            ];

            /** @var \Redis $redisProvider */
            $redisProvider = $this->container->get('app.cache.redis_provider');

            $redisProvider->select(RedisUtil::REDIS_INDEX);

            $redisProvider->hSet(RedisUtil::VIEW_PLATEU, RedisUtil::CREATE_PLATEU, json_encode($plateu));

            return $plateu;
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[PlateuService][createPlateu] %s', $e), [
                'xCoordination' => $plateuDTO->getXCoordination(),
                'yCoordination' => $plateuDTO->getYCoordination(),
                'compassCharacter' => $plateuDTO->getCompassCharacter(),
            ]);
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getPlateu(): ?array
    {
        try {
            /** @var \Redis $redisProvider */
            $redisProvider = $this->container->get('app.cache.redis_provider');
            $redisProvider->select(RedisUtil::REDIS_INDEX);

            $plateu = $redisProvider->hGet(RedisUtil::VIEW_PLATEU, RedisUtil::CREATE_PLATEU);

            return json_decode($plateu, true);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[PlateuService][createPlateu] %s', $e));
        }

        return null;
    }
}