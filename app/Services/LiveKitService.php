<?php

namespace App\Services;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;

class LiveKitService
{
    protected string $apiKey;

    protected string $apiSecret;

    protected string $url;

    public function __construct()
    {
        $this->apiKey = config('services.livekit.api_key');
        $this->apiSecret = config('services.livekit.api_secret');
        $this->url = config('services.livekit.url');
    }

    /**
     * Generate an access token for a room.
     */
    public function generateToken(string $roomName, string $participantName, bool $isHost = false): string
    {
        $tokenOptions = (new AccessTokenOptions)
            ->setIdentity($participantName)
            ->setTtl(3600); // 1 hour

        $videoGrant = (new VideoGrant)
            ->setRoomJoin(true)
            ->setRoomName($roomName);

        if ($isHost) {
            $videoGrant->setRoomCreate(true)
                ->setRoomAdmin(true);
        }

        $token = new AccessToken($this->apiKey, $this->apiSecret);
        $token->init($tokenOptions);
        $token->setGrant($videoGrant);

        return $token->toJwt();
    }
}
