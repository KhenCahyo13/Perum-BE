<?php

namespace Modules\Bill\Enums\Bill;

enum BillStatusEnum: string
{
    case Unpaid = 'unpaid';
    case Paid   = 'paid';
    case Late   = 'late';

    public function getLabel(): string
    {
        return match($this) {
            self::Unpaid => 'Belum Dibayar',
            self::Paid   => 'Lunas',
            self::Late   => 'Terlambat',
        };
    }

    public function toRaw(): string
    {
        return $this->value;
    }
}
