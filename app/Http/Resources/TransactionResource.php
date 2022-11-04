<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'reference' => $this->reference,
            'debitAccount' => $this->debit_account,
            'creditAccount' => $this->credit_account,
            'recipientName' => $this->recipient_name,
            'description' => $this->description,
            'transactionType' => $this->transaction_type,
            'amount' => number_format($this->amount),
            'status' => $this->status,
            'reason' => $this->reason,
            // 'user' => [
            //     'name' => $this->users?->first_name . ' ' . $this->users?->last_name,
            //     'email' => $this->users->email,
            //     'phone' => $this->users->phone,
            //     'date_of_birth' => $this->users->date_of_birth,
            //     'gender' => $this->users->gender,
            //     'nationality' => $this->users->nationality,
            //     'id_type' => $this->users->id_type,
            //     'id_number' => $this->users->id_number,
            //     'address' => $this->users?->address,
            // ],
        ];
    }
}
