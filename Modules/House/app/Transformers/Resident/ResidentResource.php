<?php

namespace Modules\House\Transformers\Resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $activeHistory = $this->whenLoaded('houseHistories', fn () => $this->houseHistories->first()
        );

        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'phoneNumber' => $this->phone_number,
            'isMarried' => $this->is_married,
            'residentType' => $this->resident_type->getLabel(),
            'houseNumber' => $activeHistory?->house?->house_number,
            'houseAddress' => $activeHistory?->house?->address,
        ];
    }
}
