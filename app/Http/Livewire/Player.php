<?php

namespace App\Http\Livewire;

use App\Components\HomeAssistant\Api;
use App\Models\Option;
use Livewire\Component;

class Player extends Component
{

    public string $state = '';
    public string $artist = '';
    public string $title = '';

    protected $listeners = [
        'title_changed' => 'titleChanged',
        'player_changed' => 'playerChanged',
    ];

    public function play() {
        (new Api())->play(Option::where('key', 'selected_player')->first()->value);
        $this->state = 'playing';
    }

    public function pause() {
        (new Api())->pause(Option::where('key', 'selected_player')->first()->value);
        $this->state = 'paused';
    }


    public function playerChanged($entityId) {
        $state = (new Api())->getPlayerState($entityId);
        $this->titleChanged($state);
    }

    public function titleChanged($state)
    {
        $this->state = $state['state'];
        $this->artist = $state['attributes']['media_artist'] ?? '';
        $this->title = $state['attributes']['media_title'] ?? '';
    }

    public function mount()
    {
        $player = Option::where('key', 'selected_player')->first();
        if(filled($player)) {
            try {
                $state = (new Api())->getPlayerState($player->value);
                $this->titleChanged($state);
            } catch(\Exception $ex) {
               // Handle rror
            }

        }
    }

    public function render()
    {
        return view('livewire.player');
    }
}
