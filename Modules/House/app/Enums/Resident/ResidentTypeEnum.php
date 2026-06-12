<?php

namespace Modules\House\Enums\Resident;

enum ResidentTypeEnum: string
{
    case Permanent = 'permanent';
    case Contract = 'contract';

    public function getLabel(): string
    {
        return match ($this) {
            self::Permanent => 'Tetap',
            self::Contract => 'Kontrak',
        };
    }

    public function toRaw(): string
    {
        return $this->value;
    }
}
