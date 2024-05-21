<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'roles' => RolesResource::collection($this->roles),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
