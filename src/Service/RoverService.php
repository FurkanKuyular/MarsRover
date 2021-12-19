<?php

namespace App\Service;

use App\DTO\RoverDTO;
use App\Util\RedisUtil;
use App\Util\CompassCharacterUtil;

class RoverService extends AbstractService
{
    /**
     * @param array    $plateu
     * @param RoverDTO $roverDTO
     *
     * @return array|null
     */
    public function upsertRover(array $plateu, RoverDTO $roverDTO): ?array
    {
        try {
            /** @var \Redis $redisProvider */
            $redisProvider = $this->container->get('app.cache.redis_provider');
            $redisProvider->select(RedisUtil::REDIS_INDEX);

            $rover = $redisProvider->hGet(RedisUtil::VIEW_ROVER, RedisUtil::CREATE_ROVER);

            if (empty($rover)) {
                $defaultLocation = [
                    'xCoordinate' => 0,
                    'yCoordinate' => 0,
                    'compassCharacter' => CompassCharacterUtil::NORTH,
                ];

                $redisProvider->hSet(RedisUtil::VIEW_ROVER, RedisUtil::CREATE_ROVER, json_encode($defaultLocation));

                return $defaultLocation;
            }

            $rover = json_decode($rover, true);

            $xCoordinate = $rover['xCoordinate'];
            $yCoordinate = $rover['yCoordinate'];
            $compassCharacter = $rover['compassCharacter'];

            $chars = str_split($roverDTO->getCommands());

            foreach ($chars as $char) {
                if ($char !== CompassCharacterUtil::MOVE) {
                    $compassCharacter = CompassCharacterUtil::calculateCompassCharacter($char, $compassCharacter);

                    continue;
                }

                [$xCoordinate, $yCoordinate] = CompassCharacterUtil::calculateXorYCoordination($xCoordinate, $yCoordinate, $compassCharacter);
            }

            $roverCoordinate = [
                'xCoordinate' => $xCoordinate,
                'yCoordinate' => $yCoordinate,
                'compassCharacter' => $compassCharacter,
            ];

            $redisProvider->hSet(RedisUtil::VIEW_ROVER, RedisUtil::CREATE_ROVER, json_encode($roverCoordinate));

            return $roverCoordinate;
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[RoverService][upsertRover] %s', $e), [
                'plateau' => $plateu,
                'commands' => $roverDTO->getCommands(),
            ]);
        }

        return null;
    }

    public function getRover(): ?array
    {
        try {
            $redisProvider = $this->container->get('app.cache.redis_provider');
            $redisProvider->select(RedisUtil::REDIS_INDEX);

            $rover = $redisProvider->hGet(RedisUtil::VIEW_ROVER, RedisUtil::CREATE_ROVER);

            if (empty($rover)) {
                throw new \Exception('Rover could not get in redis');
            }

            return json_decode($rover, true);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[RoverService][getRover] %s', $e));
        }

        return null;
    }
}