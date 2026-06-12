<?php

namespace Modules\House\Transformers\House;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'houseNumber' => $this->house_number,
            'address' => $this->address,
            'status' => $this->status->getLabel(),
        ];
    }
}
