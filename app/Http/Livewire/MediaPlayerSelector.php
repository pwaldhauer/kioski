<?php

namespace App\Http\Livewire;

use App\Components\HomeAssistant\Api;
use App\Components\HomeAssistant\MediaPlayer;
use App\Enums\Options;
use App\Models\Option;
use GuzzleHttp\Exception\ServerException;
use Livewire\Component;

class MediaPlayerSelector extends Component
{


    public string $playUri = '';
    public ?string $selectedPlayer = null;

    public ?string $entityId = null;

    public bool $error = false;

    protected $listeners = ['play' => 'play'];

    public function closeError() {
        $this->error = false;
    }

    public function play($payload)
    {
        $this->playUri = $payload;

        try {
            $response = $this->api()->enqueueAlbum(
                $this->entityId,
                $this->playUri
            );

            $this->emit('title_changed', [...$response, 'state' => 'playing']);
        } catch (ServerException $e) {
            $this->error = true;
        }



    }

    public function selectPlayer($entityId)
    {
        foreach ($this->mediaPlayers as $player) {
            if ($player->entityId === $entityId) {
                $this->selectedPlayer = $player->name;
                $this->entityId = $player->entityId;

                Option::upsert(
                    ['key' => 'selected_player', 'value' => $player->entityId], 'key'
                );

                $this->emit('player_changed', $player->entityId);
            }
        }

    }

    public function getMediaPlayersProperty()
    {
        if(blank(Option::for(Options::HassHost)->value)) {
            return [];
        }

        $cacheKey = 'mediaplayers';
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        cache()->set($cacheKey, $players = $this->api()->getAllMediaPlayers());

        return $players;
    }

    public function reloadCache() {
        cache()->forget('mediaplayers');
        $this->getMediaPlayersProperty();
    }

    public function mount()
    {
        $player = Option::where('key', 'selected_player')->first();
        if(filled($player)) {
            $this->selectPlayer($player->value);
        }
    }

    public function render()
    {
        return view('livewire.media-player-selector');
    }

    private function api(): Api
    {
        return new Api();
    }
}
