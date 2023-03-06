<?php

namespace App\Enums;

enum Options: string
{
    case SelectedPlayer = 'selected_player';

    case HassHost = 'hass_host';
    case HassToken = 'hass_token';

    case SpotifyClientId = 'spotify_client_id';

    case SpotifyClientSecret = 'spotify_client_secret';
}
