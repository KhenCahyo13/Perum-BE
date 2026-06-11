<?php

namespace Modules\House\Transformers\House;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class HouseDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'houseNumber' => $this->house_number,
            'address' => $this->address,
            'status' => $this->status->getLabel(),
            'currentResident' => $this->whenLoaded('residentHistories', function () {
                $resident = $this->residentHistories->where('is_active', true)->first()?->resident;

                return $resident ? [
                    'id' => $resident->id,
                    'fullName' => $resident->full_name,
                    'ktpFileUrl' => $resident->ktp_file_url ? Storage::url($resident->ktp_file_url) : null,
                    'phoneNumber' => $resident->phone_number,
                    'isMarried' => $resident->is_married,
                    'residentType' => $resident->resident_type->getLabel(),
                ] : null;
            }),
            'histories' => $this->whenLoaded('residentHistories', function () {
                if ($this->residentHistories->isEmpty()) {
                    return null;
                }

                return $this->residentHistories->map(fn ($history) => [
                    'id' => $history->id,
                    'startDate' => $history->start_date,
                    'endDate' => $history->end_date,
                    'isActive' => $history->is_active,
                    'residentName' => $history->resident?->full_name,
                    'residentKtpFileUrl' => $history->resident?->ktp_file_url
                        ? Storage::url($history->resident->ktp_file_url)
                        : null,
                    'residentPhoneNumber' => $history->resident?->phone_number,
                ])->values()->all();
            }),
        ];
    }
}
