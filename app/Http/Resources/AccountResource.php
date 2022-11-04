<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // $user = User::where('cif', $this->cif)->firstOrFail();
        // $creator = User::where('id', $this->created_by)->firstOrFail();
        // $blocker = User::where('id', $this->blocked_by)->firstOrFail();
        // $user = User::first();
        // $creator = User::first();
        // $blocker = User::first();
        return [
            'customer_id' => $this->cif,
            'account_number' => $this->account_id,
            'account_balance' => number_format($this->balance),
            'last_transacted_at' => $this->last_transacted_at,
            'created_by' => $this->createdBy->first_name . ' ' . $this->createdBy->last_name, //Show names here through relationships
            'created_at' => $this->created_at->toDateTimeString(),
            'is_blocked' => $this->blocked,
            // Only show the blocked fields only when the account is blocked
            $this->mergeWhen($this->blocked, [
                'blocked_by' => $this->blockedBy?->first_name . ' ' . $this->blockedBy?->last_name, //Show names here through relationships
                'blocked_at' => $this->blocked_at,
            ]),
            'user' => [
                'name' => $this->users?->first_name . ' ' . $this->users?->last_name,
                'email' => $this->users->email,
                'phone' => $this->users->phone,
                'date_of_birth' => $this->users->date_of_birth,
                'gender' => $this->users->gender,
                'nationality' => $this->users->nationality,
                'id_type' => $this->users->id_type,
                'id_number' => $this->users->id_number,
                'address' => $this->users?->address,
            ],
        ];
    }
}
