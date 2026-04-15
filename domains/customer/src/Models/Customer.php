<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    public const GENDER_UNKNOWN = 'u';

    public const GENDER_MALE = 'm';

    public const GENDER_FEMALE = 'f';

    protected $table = 'shop_customers';

    protected $fillable = [
        'email',
        'email_canonical',
        'first_name',
        'last_name',
        'birthday',
        'gender',
        'customer_group_id',
        'phone_number',
        'subscribed_to_newsletter',
        'user_id',
        'default_address_id',
    ];

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
            'subscribed_to_newsletter' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Customer $customer): void {
            $customer->email_canonical = $customer->email !== null
                ? mb_strtolower($customer->email)
                : null;
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function defaultAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'default_address_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s', $this->first_name ?? '', $this->last_name ?? ''));
    }

    public function isMale(): bool
    {
        return $this->gender === self::GENDER_MALE;
    }

    public function isFemale(): bool
    {
        return $this->gender === self::GENDER_FEMALE;
    }

    /**
     * Default address belongs to this customer (single default per customer).
     */
    public function setDefaultAddress(?Address $address): void
    {
        if ($address !== null) {
            $address->customer_id = $this->id;
            $address->save();
        }

        $this->update(['default_address_id' => $address?->id]);
    }
}
