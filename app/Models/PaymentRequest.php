<?php

namespace App\Models;

use App\Enums\PaymentRequestStatus;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Carbon\Carbon;
use Database\Factories\PaymentRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property string $invoice
 * @property numeric $amount
 * @property string $currency
 * @property numeric $exchange_rate
 * @property string $exchange_rate_source
 * @property Carbon $exchanged_at
 * @property numeric $eur_amount
 * @property PaymentRequestStatus $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $approver
 * @property-read User $user
 *
 * @method static PaymentRequestFactory factory($count = null, $state = [])
 *
 * @mixin Eloquent
 */
class PaymentRequest extends Model
{
    /** @use HasFactory<PaymentRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'invoice',
        'amount',
        'currency',
        'exchange_rate',
        'exchange_rate_source',
        'exchanged_at',
        'eur_amount',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'eur_amount' => 'decimal:2',
            'exchanged_at' => 'datetime',
            'approved_at' => 'datetime',
            'status' => PaymentRequestStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
