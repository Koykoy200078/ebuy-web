<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tracking_no' => $this->tracking_no,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'pincode' => $this->pincode,
            'address' => $this->address,
            'status_message' => $this->status_message,
            'payment_mode' => $this->payment_mode,
            'payment_id' => $this->payment_id,
            'confirm' => $this->confirm,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
