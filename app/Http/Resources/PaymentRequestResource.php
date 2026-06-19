<?php

namespace App\Http\Resources;

use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin PaymentRequest
 */
class PaymentRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'description' => $this->description,
            'invoice' => Storage::url($this->invoice),
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'exchange_rate' => (float) $this->exchange_rate,
            'exchange_rate_source' => $this->exchange_rate_source,
            'exchanged_at' => $this->exchanged_at,
            'eur_amount' => (float) $this->eur_amount,
            'status' => $this->status->value,
            'approved_by' => $this->when($this->approved_by, fn () => UserResource::make($this->whenLoaded('approver'))),
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
