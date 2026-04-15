<?php

namespace Modules\Catalog\Providers;

use Modules\Catalog\Repositories\Contracts\ProductAssociationRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductAssociationTypeRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductAttributeRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductImageRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductOptionRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductOptionValueRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\ProductVariantRepositoryInterface;
use Modules\Catalog\Repositories\Contracts\TaxonRepositoryInterface;
use Modules\Catalog\Repositories\ProductAssociationRepository;
use Modules\Catalog\Repositories\ProductAssociationTypeRepository;
use Modules\Catalog\Repositories\ProductAttributeRepository;
use Modules\Catalog\Repositories\ProductAttributeValueRepository;
use Modules\Catalog\Repositories\ProductImageRepository;
use Modules\Catalog\Repositories\ProductOptionRepository;
use Modules\Catalog\Repositories\ProductOptionValueRepository;
use Modules\Catalog\Repositories\ProductRepository;
use Modules\Catalog\Repositories\ProductVariantRepository;
use Modules\Catalog\Repositories\TaxonRepository;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(ProductAssociationRepositoryInterface::class, ProductAssociationRepository::class);
		$this->app->bind(ProductAssociationTypeRepositoryInterface::class, ProductAssociationTypeRepository::class);
		$this->app->bind(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
		$this->app->bind(ProductAttributeValueRepositoryInterface::class, ProductAttributeValueRepository::class);
		$this->app->bind(ProductImageRepositoryInterface::class, ProductImageRepository::class);
		$this->app->bind(ProductOptionRepositoryInterface::class, ProductOptionRepository::class);
		$this->app->bind(ProductOptionValueRepositoryInterface::class, ProductOptionValueRepository::class);
		$this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
		$this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);
		$this->app->bind(TaxonRepositoryInterface::class, TaxonRepository::class);
	}
	
	public function boot(): void
	{
		$this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
	}
}
