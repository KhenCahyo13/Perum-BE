<?php

namespace Modules\House\Transformers\Resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ResidentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $activeHistory = $this->whenLoaded('houseHistories', fn () => $this->houseHistories->first()
        );

        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'ktpFileUrl' => $this->ktp_file_url ? Storage::url($this->ktp_file_url) : null,
            'phoneNumber' => $this->phone_number,
            'isMarried' => $this->is_married,
            'residentType' => $this->resident_type->getLabel(),
            'houseNumber' => $activeHistory?->house?->house_number,
            'houseAddress' => $activeHistory?->house?->address,
        ];
    }
}
