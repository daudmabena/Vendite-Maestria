<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

/**
 * Product or variant image path — store paths relative to the configured disk (e.g. public).
 */
class ProductImage extends Model
{
    protected $table = 'shop_product_images';

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'path',
        'position',
        'mime_type',
        'original_name',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'url',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProductImage $image): void {
            if ($image->product_variant_id === null) {
                return;
            }

            $variant = ProductVariant::query()->find($image->product_variant_id);
            if ($variant === null) {
                throw new InvalidArgumentException('Product variant does not exist for this image.');
            }

            if ((int) $variant->product_id !== (int) $image->product_id) {
                throw new InvalidArgumentException('Variant must belong to the same product as the image.');
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Public URL when using the public disk and symlinked storage.
     */
    public function getUrlAttribute(): string
    {
        if ($this->path === null || $this->path === '') {
            return '';
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->path);
    }
}
