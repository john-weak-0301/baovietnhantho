<?php

namespace App\Http\Resources;

use App\Model\Branch;
use App\Model\Ward;
use App\Model\District;
use App\Model\Province;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BranchResource
 *
 * @mixin Branch
 *
 * @package App\Http\Resources
 */
class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'type'         => $this->type,
            'address'      => $this->address,
            'position'     => [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ],
            'phone_number' => $this->phone_number,
            'fax_number'   => $this->fax_number,
            'email'        => $this->email,
            'services'     => BranchServiceResources::collection($this->whenLoaded('services')),
            'working_day'  => $this->working_days ?: [],
        ];
    }

    public function getFormattedAddress()
    {
        return rescue(function () {
            return sprintf(
                '%s, %s, %s, %s',
                $this->address,
                Ward::getByCode($this->ward_code, $this->district_code)->getName(),
                District::getByCode($this->district_code)->getName(),
                Province::getByCode($this->province_code)->getName()
            );
        });
    }
}
