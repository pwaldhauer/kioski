<?php

namespace App\Components\HomeAssistant;

class MediaPlayer
{

    public string $entityId;
    public string $name;

    public static function fromArray(array $arr): self
    {
        $obj = new MediaPlayer();

        $obj->name = $arr['attributes']['friendly_name'];
        $obj->entityId = $arr['entity_id'];

        return $obj;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
