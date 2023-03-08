<?php

namespace App\Components\HomeAssistant;

use App\Enums\Options;
use App\Models\Option;
use GuzzleHttp\Client;

class Api
{
    private $client = null;

    private function client()
    {
        if (empty($this->client)) {
            $this->client = new Client([
                'connect_timeout' => 1,
                'base_uri' => Option::for(Options::HassHost)->value,
                'headers' => [
                    'Authorization' => 'Bearer ' . Option::for(Options::HassToken)->value,
                ]
            ]);
        }


        return $this->client;
    }

    public function getAllMediaPlayers()
    {
        $response = $this->client()->get('/api/states')->getBody();

        $data = array_filter(json_decode($response, true),
            function ($item) {
                return str_starts_with($item['entity_id'], 'media_player.');
            }
        );

        return array_map(fn($item) => MediaPlayer::fromArray($item), $data);
    }

    public function getPlayerState($entityId)
    {
        $response = $this->client()->get('/api/states/' . $entityId)->getBody();
        $json = json_decode($response, true);
        return $json;
    }

    public function enqueueAlbum($entityId, $url, $enq = 'play')
    {
        return $this->request('/api/services/media_player/play_media', [
            'entity_id' => $entityId,
            'media_content_id' => $url,
            'media_content_type' => 'album',
            'enqueue' => $enq
        ]);
    }

    public function setRepeatAll($entityId)
    {
        $this->request('/api/services/media_player/repeat_set', [
            'entity_id' => $entityId,
            'repeat' => 'all'
        ]);
    }

    public function skipTitle($entityId)
    {
        return $this->request('/api/services/media_player/media_next_track', [
            'entity_id' => $entityId,
        ]);
    }

    public function play($entityId)
    {
        $this->request('/api/services/media_player/media_play', [
            'entity_id' => $entityId
        ]);
    }

    public function pause($entityId)
    {
        $this->request('/api/services/media_player/media_pause', [
            'entity_id' => $entityId
        ]);
    }

    public function stop($entityId)
    {
        $this->request('/api/services/media_player/media_stop', [
            'entity_id' => $entityId
        ]);
    }

    private function request($url, $payload)
    {
        $response = $this->client()->post($url, [
            'body' => json_encode($payload)
        ]);

        $json = json_decode($response->getBody(), true);
        if (empty($json)) {
            return [];
        }

        return $json[0];
        /**
         * response looks like that, maybe there is something interesting in there
         * [{
         * "entity_id": "media_player.wohnzimmer_ham",
         * "state": "paused",
         * "attributes": {
         * "group_members": ["media_player.wohnzimmer_ham"],
         * "volume_level": 0.37,
         * "is_volume_muted": false,
         * "media_content_id": "x-sonos-spotify:spotify%3atrack%3a6qlVOE9KNcXFvLsn1btxz9?sid=9&flags=8232&sn=1",
         * "media_content_type": "music",
         * "media_duration": 206,
         * "media_position_updated_at": "2023-02-27T18:34:22.072623+00:00",
         * "media_title": "Rettung",
         * "media_artist": "Kettcar",
         * "media_album_name": "Zwischen den Runden",
         * "shuffle": false,
         * "repeat": "off",
         * "queue_position": 2,
         * "queue_size": 26,
         * "entity_picture": "/api/media_player_proxy/media_player.wohnzimmer_ham?token=c61570689972b228b36c7edf9fdb73bdc16b46b3401d9372498e45f628e8d2a2&cache=5c2d83e1bb7aaa66",
         * "friendly_name": "Sonos Wohnzimmer",
         * "supported_features": 981567
         * },
         * "last_changed": "2023-02-27T18:34:22.140558+00:00",
         * "last_updated": "2023-02-27T18:34:22.140558+00:00",
         * "context": {
         * "id": "01GTA2EZMQFSXF9WK8W9M3ZMXC",
         * "parent_id": null,
         * "user_id": "680f3616a4884be7a0bd608351e7e916"
         * }
         * }]
         */
    }
}
