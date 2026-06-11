<?php

namespace Modules\House\Enums\House;

enum StatusEnum: string
{
    case Occupied = 'occupied';
    case Vacant = 'vacant';

    public function getLabel(): string
    {
        return match ($this) {
            self::Occupied => 'Dihuni',
            self::Vacant => 'Tidak Dihuni',
        };
    }

    public function toRaw(): string
    {
        return $this->value;
    }
}
