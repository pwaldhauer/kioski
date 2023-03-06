<?php

namespace App\Http\Livewire;

use App\Components\HomeAssistant\Api as HassApi;
use App\Components\Spotify\Api as SpotifyApi;
use App\Enums\Options;
use App\Models\Option;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CredentialsPanel extends Component
{

    public array $credentials = [];

    public function rules()
    {
        return [
            'credentials.' . Options::HassHost->value => 'string',
            'credentials.' . Options::HassToken->value => 'string',
            'credentials.' . Options::SpotifyClientId->value => 'string',
            'credentials.' . Options::SpotifyClientSecret->value => 'string',
        ];
    }

    public function getHassConfiguredProperty()
    {
        return filled(Option::for(Options::HassHost)->value) && filled(Option::for(Options::HassToken)->value);
    }

    public function getSpotifyConfiguredProperty()
    {
        return filled(Option::for(Options::SpotifyClientId)->value) && filled(Option::for(Options::SpotifyClientSecret)->value);
    }

    public function mount()
    {
        foreach ([Options::HassHost, Options::SpotifyClientId] as $enum) {
            $this->credentials[$enum->value] = Option::for($enum)->value;
        }
    }

    public function submitHass()
    {
        $this->save([Options::HassHost, Options::HassToken]);

        try {
            $api = new HassApi();
            $api->getAllMediaPlayers();
        } catch (\Exception $ex) {

            Option::for(Options::HassToken)->update(['value' => null]);

            throw ValidationException::withMessages([
                'credentials.' . Options::HassHost->value => 'Could not connect to Home Assistant, please check your credentials.',
            ]);
        }
    }

    public function submitSpotify()
    {
        $this->save([Options::SpotifyClientId, Options::SpotifyClientSecret]);

        try {
            $api = new SpotifyApi();
            $api->fetchArtistInfo('4VrqQQy6X0hlMtqY5gp6Wx');
        } catch (\Exception $ex) {
            Option::for(Options::SpotifyClientSecret)->update(['value' => null]);

            throw ValidationException::withMessages([
                'credentials.' . Options::SpotifyClientId->value => 'Cannot connect to spotify.',
            ]);
        }
    }

    private function save($keys)
    {
        foreach ($keys as $key) {
            $value = $this->credentials[$key->value] ?? null;
            if (blank($value)) {
                continue;
            }

            $option = Option::for($key);
            $option->fill(['value' => $value]);

            $option->save();
        }
    }

    public function render()
    {
        return view('livewire.credentials-panel');
    }
}
