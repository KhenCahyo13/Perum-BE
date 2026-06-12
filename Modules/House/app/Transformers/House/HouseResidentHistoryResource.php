<?php

namespace Modules\House\Transformers\House;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\House\Transformers\Resident\ResidentResource;

class HouseResidentHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'isActive' => $this->is_active,
            'resident' => new ResidentResource($this->whenLoaded('resident')),
        ];
    }
}
