<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taxon extends Model
{
    protected $table = 'shop_taxons';

    protected $fillable = [
        'code',
        'parent_id',
        'position',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Taxon::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Taxon::class, 'parent_id')->orderBy('position');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TaxonTranslation::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_product_taxon', 'taxon_id', 'product_id')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function translate(?string $locale = null): ?TaxonTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations()->where('locale', $locale)->first()
            ?? $this->translations()->first();
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }
}
