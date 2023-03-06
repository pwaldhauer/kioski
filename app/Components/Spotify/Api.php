<?php

namespace App\Components\Spotify;

use App\Enums\Options;
use App\Models\Option;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class Api
{

    public function fetchArtistInfo($id)
    {
        return $this->getClient()->getArtist($id);
    }

    public function fetchAlbumInfo($id)
    {
        return $this->getClient()->getAlbum($id);
    }

    public function fetchPlaylistInfo($id)
    {
        return $this->getClient()->getPlaylist($id);
    }

    public function fetchAllAlbumsForArtist($id)
    {
        $cacheKey = 'spotify.albums.' . $id;
        if (cache()->has($cacheKey)) {
            //  return cache()->get($cacheKey);
        }

        $all = [];

        $page = 0;
        do {
            $result = $this->getClient()->getArtistAlbums($id, ['limit' => 50, 'offset' => $page * 50]);

            $albums = array_map(
                fn($album) => (array)$album,
                $result->items
            );

            $all = array_merge($all, $albums);

            $page++;
        } while (!empty($result->next));

        cache()->set($cacheKey, $all);

        return $all;
    }

    public function isConfigured(): bool
    {
        return !empty(Option::for(Options::SpotifyClientId)->value)
            && !empty(Option::for(Options::SpotifyClientSecret)->value);
    }

    private function getClient()
    {
        $session = new Session(
            Option::for(Options::SpotifyClientId)->value,
            Option::for(Options::SpotifyClientSecret)->value,
        );

        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();

        $api = new SpotifyWebAPI();
        $api->setAccessToken($accessToken);

        return $api;
    }
}
