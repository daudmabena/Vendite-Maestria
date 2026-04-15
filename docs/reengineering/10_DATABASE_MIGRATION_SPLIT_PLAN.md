# 10 — Database Migration Split Plan

> **Scope**: Move every shop table migration from `database/migrations/` into its
> owning domain module under `domains/{domain}/database/migrations/`. Each table
> gets its own migration file. Migration timestamps are globally coordinated so
> Laravel's cross-path sort order respects all foreign-key dependencies.

---

## 1. Guiding rules

1. **One table per file.** Each migration creates or alters exactly one table.
2. **Referenced before referencing.** Any table `B` with a FK to table `A` must have
   a higher timestamp than `A`'s migration — across all domain paths.
3. **Alter migrations are separate files.** Adding a cross-domain FK column is a
   distinct migration owned by the table that receives the new column.
4. **Framework migrations stay in `database/migrations/`** — `users`, `cache`, `jobs`,
   `personal_access_tokens`, `add_is_admin_to_users`. Do not move these.
5. **ServiceProvider registers paths.** Each domain's `ServiceProvider::boot()` calls
   `$this->loadMigrationsFrom(__DIR__ . '/../../database/migrations')`. Laravel merges
   all registered paths and executes migrations sorted by filename timestamp globally.
6. **Timestamps are globally unique.** Use the format `YYYY_MM_DD_HHMMSS`. Timestamps
   within the same domain are incremented by 1 second. Across domains the block ranges
   below guarantee no collision and correct cross-domain ordering.

---

## 2. Timestamp block allocation

Each domain owns a timestamp sub-range. Migrations within a block are ordered by
second-level increments.

| Domain          | Block start          | Tables (count) |
|-----------------|----------------------|----------------|
| **shop-core**   | `2026_04_13_120001`  | 13             |
| **customer**    | `2026_04_13_130001`  | 4              |
| **catalog**     | `2026_04_13_140001`  | 21             |
| **checkout**    | `2026_04_13_150001`  | 9              |
| **fulfillment** | `2026_04_13_160001`  | 4              |
| **promotion**   | `2026_04_13_170001`  | 5              |
| **content**     | `2026_04_13_180001`  | 1              |
| **reporting**   | — (no tables yet)    | —              |

---

## 3. Dependency graph and creation order

Read left-to-right: a table on the right depends on everything to its left.

```
users
 └─ shop_customers (via user_id)

shop_currencies
shop_locales
 └─ shop_channels (via base_currency_id, default_locale_id)
    ├─ shop_channel_currency   (pivot)
    ├─ shop_channel_locale     (pivot)
    ├─ shop_channel_country    (pivot → shop_countries)
    ├─ shop_channel_product    (pivot → shop_products)
    ├─ shop_shipping_method_channel (pivot → shop_shipping_methods)
    ├─ shop_payment_method_channel  (pivot → shop_payment_methods)
    └─ shop_promotion_channel  (pivot → shop_promotions)

shop_countries
 └─ shop_provinces
 └─ shop_channel_country

shop_zones
 └─ shop_zone_members
 └─ shop_shipping_methods (via zone_id)

shop_tax_categories
 └─ shop_tax_rates
 └─ shop_product_variants.tax_category_id   (ALTER — catalog owns)

shop_shipping_categories
 └─ shop_shipping_methods (via shipping_category_id)
 └─ shop_product_variants.shipping_category_id (ALTER — catalog owns)

shop_customer_groups
 └─ shop_customers (via customer_group_id)

shop_customers  ← created first without default_address_id
 └─ shop_addresses (via customer_id)
      └─ ALTER shop_customers add default_address_id
 └─ shop_orders (via customer_id)
 └─ shop_product_reviews (via customer_id)

shop_products
 ├─ shop_product_translations
 ├─ shop_product_variants
 │    ├─ shop_product_variant_translations
 │    ├─ shop_channel_pricings
 │    ├─ ALTER: add on_hand/on_hold/tracked columns
 │    ├─ ALTER: add tax_category_id FK
 │    ├─ ALTER: add shipping_category_id FK
 │    ├─ shop_product_variant_option_values
 │    └─ shop_product_images (optional FK)
 ├─ shop_channel_product
 ├─ shop_product_product_option (pivot)
 ├─ shop_product_attribute_values
 ├─ shop_product_associations (via owner_product_id)
 ├─ shop_product_association_product (pivot)
 ├─ shop_product_images
 ├─ shop_product_reviews
 └─ ALTER: add main_taxon_id FK (after shop_taxons exists)

shop_taxons (self-referencing)
 └─ shop_taxon_translations
 └─ shop_product_taxon (pivot)
 └─ ALTER shop_products add main_taxon_id

shop_product_options
 └─ shop_product_option_values
 └─ shop_product_product_option (pivot)
 └─ shop_product_variant_option_values

shop_product_association_types
 └─ shop_product_associations

shop_product_attributes
 └─ shop_product_attribute_values

shop_orders (via channel_id, customer_id, shipping/billing address_id)
 ├─ shop_order_items (via product_variant_id)
 │    └─ shop_order_item_units
 │         └─ shop_shipment_units
 ├─ shop_adjustments (polymorphic, no FK constraint)
 ├─ shop_shipments (via order_id)
 └─ shop_payments (via order_id)
 └─ ALTER: add promotion_coupon_id FK (after shop_promotion_coupons exists)

shop_payment_methods
 └─ shop_payment_method_channel
 └─ shop_payments (via payment_method_id)

shop_shipping_methods (via shipping_category_id, zone_id)
 └─ shop_shipping_method_channel
 └─ shop_shipments (via shipping_method_id)

shop_shipments
 └─ shop_shipment_units

shop_promotions
 ├─ shop_promotion_channel
 ├─ shop_promotion_rules
 ├─ shop_promotion_actions
 └─ shop_promotion_coupons
      └─ ALTER shop_orders add promotion_coupon_id
```

---

## 4. Domain migration file manifest

### 4.1 `domains/shop-core/database/migrations/`

All tables here have no inter-domain FK dependencies (they are referenced by others).

| Filename                                                   | Table(s) created / altered                  | Depends on |
|------------------------------------------------------------|---------------------------------------------|------------|
| `2026_04_13_120001_create_shop_currencies_table.php`       | `shop_currencies`                           | —          |
| `2026_04_13_120002_create_shop_locales_table.php`          | `shop_locales`                              | —          |
| `2026_04_13_120003_create_shop_countries_table.php`        | `shop_countries`                            | —          |
| `2026_04_13_120004_create_shop_zones_table.php`            | `shop_zones`                                | —          |
| `2026_04_13_120005_create_shop_tax_categories_table.php`   | `shop_tax_categories`                       | —          |
| `2026_04_13_120006_create_shop_shipping_categories_table.php` | `shop_shipping_categories`               | —          |
| `2026_04_13_120007_create_shop_channels_table.php`         | `shop_channels`                             | currencies, locales |
| `2026_04_13_120008_create_shop_channel_currency_table.php` | `shop_channel_currency`                     | channels, currencies |
| `2026_04_13_120009_create_shop_channel_locale_table.php`   | `shop_channel_locale`                       | channels, locales |
| `2026_04_13_120010_create_shop_provinces_table.php`        | `shop_provinces`                            | countries  |
| `2026_04_13_120011_create_shop_zone_members_table.php`     | `shop_zone_members`                         | zones      |
| `2026_04_13_120012_create_shop_tax_rates_table.php`        | `shop_tax_rates`                            | tax_categories |
| `2026_04_13_120013_create_shop_channel_country_table.php`  | `shop_channel_country`                      | channels, countries |

---

### 4.2 `domains/customer/database/migrations/`

Depends on: `users` (framework), `shop_countries` (shop-core).

| Filename                                                         | Table(s) created / altered                | Depends on |
|------------------------------------------------------------------|-------------------------------------------|------------|
| `2026_04_13_130001_create_shop_customer_groups_table.php`        | `shop_customer_groups`                    | —          |
| `2026_04_13_130002_create_shop_customers_table.php`              | `shop_customers` *(without `default_address_id`)* | customer_groups, users |
| `2026_04_13_130003_create_shop_addresses_table.php`              | `shop_addresses`                          | shop_customers |
| `2026_04_13_130004_add_default_address_to_shop_customers_table.php` | ALTER `shop_customers` add `default_address_id` FK | shop_addresses |

> **Note on circular FK**: `shop_customers` ↔ `shop_addresses` is a circular reference.
> Resolve by creating `shop_customers` first without `default_address_id`, then
> `shop_addresses` with `customer_id` FK, then ALTER `shop_customers` to add
> `default_address_id` FK. This is already the pattern in the original migration.

---

### 4.3 `domains/catalog/database/migrations/`

Depends on: `shop_channels` (shop-core), `shop_tax_categories` (shop-core),
`shop_shipping_categories` (shop-core), `shop_customers` (customer).

#### Core product tables (no cross-domain FK at creation time)

| Filename                                                               | Table(s) created / altered                      | Depends on |
|------------------------------------------------------------------------|-------------------------------------------------|------------|
| `2026_04_13_140001_create_shop_products_table.php`                     | `shop_products` *(without `main_taxon_id`)*     | —          |
| `2026_04_13_140002_create_shop_product_translations_table.php`         | `shop_product_translations`                     | shop_products |
| `2026_04_13_140003_create_shop_product_variants_table.php`             | `shop_product_variants` *(base columns only)*   | shop_products |
| `2026_04_13_140004_create_shop_product_variant_translations_table.php` | `shop_product_variant_translations`             | shop_product_variants |
| `2026_04_13_140005_create_shop_channel_product_table.php`              | `shop_channel_product`                          | shop_channels, shop_products |
| `2026_04_13_140006_add_inventory_to_shop_product_variants_table.php`   | ALTER `shop_product_variants` add `on_hand`, `on_hold`, `tracked` | shop_product_variants |
| `2026_04_13_140007_create_shop_channel_pricings_table.php`             | `shop_channel_pricings`                         | shop_product_variants, shop_channels |

#### Taxonomy

| Filename                                                         | Table(s) created / altered                    | Depends on |
|------------------------------------------------------------------|-----------------------------------------------|------------|
| `2026_04_13_140008_create_shop_taxons_table.php`                 | `shop_taxons`                                 | shop_taxons (self) |
| `2026_04_13_140009_create_shop_taxon_translations_table.php`     | `shop_taxon_translations`                     | shop_taxons |
| `2026_04_13_140010_add_main_taxon_to_shop_products_table.php`    | ALTER `shop_products` add `main_taxon_id` FK  | shop_taxons |
| `2026_04_13_140011_create_shop_product_taxon_table.php`          | `shop_product_taxon`                          | shop_products, shop_taxons |

#### Associations

| Filename                                                              | Table(s) created / altered              | Depends on |
|-----------------------------------------------------------------------|-----------------------------------------|------------|
| `2026_04_13_140012_create_shop_product_association_types_table.php`   | `shop_product_association_types`        | —          |
| `2026_04_13_140013_create_shop_product_associations_table.php`        | `shop_product_associations`             | shop_products, shop_product_association_types |
| `2026_04_13_140014_create_shop_product_association_product_table.php` | `shop_product_association_product`      | shop_product_associations, shop_products |

#### Options and attributes

| Filename                                                                  | Table(s) created / altered                  | Depends on |
|---------------------------------------------------------------------------|---------------------------------------------|------------|
| `2026_04_13_140015_create_shop_product_options_table.php`                 | `shop_product_options`                      | —          |
| `2026_04_13_140016_create_shop_product_option_values_table.php`           | `shop_product_option_values`                | shop_product_options |
| `2026_04_13_140017_create_shop_product_product_option_table.php`          | `shop_product_product_option`               | shop_products, shop_product_options |
| `2026_04_13_140018_create_shop_product_variant_option_values_table.php`   | `shop_product_variant_option_values`        | shop_product_variants, shop_product_option_values, shop_product_options |
| `2026_04_13_140019_create_shop_product_attributes_table.php`              | `shop_product_attributes`                   | —          |
| `2026_04_13_140020_create_shop_product_attribute_values_table.php`        | `shop_product_attribute_values`             | shop_products, shop_product_attributes |

#### Cross-domain FK alters (catalog table, shop-core reference)

| Filename                                                                        | Table(s) altered                                    | Depends on |
|---------------------------------------------------------------------------------|-----------------------------------------------------|------------|
| `2026_04_13_140021_add_tax_shipping_category_to_product_variants_table.php`     | ALTER `shop_product_variants` add `tax_category_id`, `shipping_category_id` | shop_tax_categories (shop-core), shop_shipping_categories (shop-core) — timestamps 120005/120006 |

#### Media and reviews

| Filename                                                      | Table(s) created      | Depends on |
|---------------------------------------------------------------|-----------------------|------------|
| `2026_04_13_140022_create_shop_product_images_table.php`      | `shop_product_images` | shop_products, shop_product_variants |
| `2026_04_13_140023_create_shop_product_reviews_table.php`     | `shop_product_reviews` | shop_products, shop_customers (customer domain — timestamp 130002) |

---

### 4.4 `domains/checkout/database/migrations/`

Depends on: `shop_customers` (customer), `shop_channels` (shop-core),
`shop_addresses` (customer), `shop_product_variants` (catalog),
`shop_promotion_coupons` (promotion — added via ALTER after promotion block).

| Filename                                                              | Table(s) created / altered                         | Depends on |
|-----------------------------------------------------------------------|----------------------------------------------------|------------|
| `2026_04_13_150001_create_shop_orders_table.php`                      | `shop_orders` *(without `promotion_coupon_id`)*    | shop_customers, shop_channels, shop_addresses |
| `2026_04_13_150002_create_shop_order_items_table.php`                 | `shop_order_items`                                 | shop_orders, shop_product_variants |
| `2026_04_13_150003_create_shop_order_item_units_table.php`            | `shop_order_item_units`                            | shop_order_items |
| `2026_04_13_150004_create_shop_adjustments_table.php`                 | `shop_adjustments` *(polymorphic — no FK)*         | — (polymorphic morph) |
| `2026_04_13_150005_create_shop_payment_methods_table.php`             | `shop_payment_methods`                             | —          |
| `2026_04_13_150006_create_shop_payment_method_channel_table.php`      | `shop_payment_method_channel`                      | shop_payment_methods, shop_channels |
| `2026_04_13_150007_create_shop_payments_table.php`                    | `shop_payments`                                    | shop_orders, shop_payment_methods |
| `2026_04_13_150008_add_promotion_coupon_to_shop_orders_table.php`     | ALTER `shop_orders` add `promotion_coupon_id` FK   | shop_promotion_coupons (promotion — timestamp 170005) |

> **Note**: `add_promotion_coupon_to_shop_orders_table` has timestamp `2026_04_13_150008`
> but **must run after** promotion coupons (timestamp `2026_04_13_170005`). This is a
> cross-domain ordering conflict. Resolution: bump this file's timestamp to
> `2026_04_13_170006` so it sorts after the promotion block.
> Final filename: `2026_04_13_170006_add_promotion_coupon_to_shop_orders_table.php`

---

### 4.5 `domains/fulfillment/database/migrations/`

Depends on: `shop_shipping_categories`, `shop_zones` (shop-core),
`shop_channels` (shop-core), `shop_orders` (checkout),
`shop_order_item_units` (checkout).

| Filename                                                           | Table(s) created      | Depends on |
|--------------------------------------------------------------------|-----------------------|------------|
| `2026_04_13_160001_create_shop_shipping_methods_table.php`         | `shop_shipping_methods` | shop_shipping_categories, shop_zones |
| `2026_04_13_160002_create_shop_shipping_method_channel_table.php`  | `shop_shipping_method_channel` | shop_shipping_methods, shop_channels |
| `2026_04_13_160003_create_shop_shipments_table.php`                | `shop_shipments`      | shop_orders, shop_shipping_methods |
| `2026_04_13_160004_create_shop_shipment_units_table.php`           | `shop_shipment_units`  | shop_shipments, shop_order_item_units |

---

### 4.6 `domains/promotion/database/migrations/`

Depends on: `shop_channels` (shop-core).

| Filename                                                      | Table(s) created         | Depends on |
|---------------------------------------------------------------|--------------------------|------------|
| `2026_04_13_170001_create_shop_promotions_table.php`          | `shop_promotions`        | —          |
| `2026_04_13_170002_create_shop_promotion_channel_table.php`   | `shop_promotion_channel` | shop_promotions, shop_channels |
| `2026_04_13_170003_create_shop_promotion_rules_table.php`     | `shop_promotion_rules`   | shop_promotions |
| `2026_04_13_170004_create_shop_promotion_actions_table.php`   | `shop_promotion_actions` | shop_promotions |
| `2026_04_13_170005_create_shop_promotion_coupons_table.php`   | `shop_promotion_coupons` | shop_promotions |

---

### 4.7 `domains/content/database/migrations/`

No cross-domain FKs.

| Filename                                                        | Table(s) created         | Depends on |
|-----------------------------------------------------------------|--------------------------|------------|
| `2026_04_13_180001_create_shop_contact_messages_table.php`      | `shop_contact_messages`  | —          |

---

### 4.8 `database/migrations/` (framework — unchanged)

These files stay in the root migrations directory.

| Filename                                              | Purpose |
|-------------------------------------------------------|---------|
| `0001_01_01_000000_create_users_table.php`            | Laravel users + sessions + password_reset |
| `0001_01_01_000001_create_cache_table.php`            | Laravel cache |
| `0001_01_01_000002_create_jobs_table.php`             | Laravel queue jobs |
| `2026_04_14_120000_create_personal_access_tokens_table.php` | Sanctum tokens |
| `2026_04_14_130000_add_is_admin_to_users_table.php`   | Admin flag on users |

---

## 5. Cross-domain FK ordering cheat-sheet

Cross-domain ALTERs are the hardest constraint. The file that adds a FK to a table in
another domain must have a timestamp **strictly greater** than the migration that
created the referenced table.

| ALTER file (lives in domain)                                      | Referenced table (lives in domain) | Reference timestamp | ALTER timestamp required |
|-------------------------------------------------------------------|------------------------------------|---------------------|--------------------------|
| `add_tax_shipping_category_to_product_variants` (catalog)        | `shop_tax_categories` (shop-core)  | `120005`            | `> 120005` → use `140021` ✓ |
| `add_tax_shipping_category_to_product_variants` (catalog)        | `shop_shipping_categories` (shop-core) | `120006`        | `> 120006` → use `140021` ✓ |
| `add_main_taxon_to_shop_products` (catalog)                       | `shop_taxons` (catalog)            | `140008`            | `> 140008` → use `140010` ✓ |
| `add_default_address_to_shop_customers` (customer)               | `shop_addresses` (customer)        | `130003`            | `> 130003` → use `130004` ✓ |
| `add_promotion_coupon_to_shop_orders` (checkout)                 | `shop_promotion_coupons` (promotion) | `170005`          | `> 170005` → use `170006` ✓ |
| `add_tax_shipping_category_to_product_variants` (catalog)        | `shop_product_variants` (catalog)  | `140003`            | `> 140003` → use `140021` ✓ |

---

## 6. ServiceProvider registration

Each domain ServiceProvider `boot()` must register its migrations path.

```php
// Example: domains/shop-core/src/Providers/ShopCoreServiceProvider.php
public function boot(): void
{
    $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
}
```

Apply the same pattern to every domain:
- `CatalogServiceProvider` → `domains/catalog/database/migrations`
- `CheckoutServiceProvider` → `domains/checkout/database/migrations`
- `ContentServiceProvider` → `domains/content/database/migrations`
- `CustomerServiceProvider` → `domains/customer/database/migrations`
- `FulfillmentServiceProvider` → `domains/fulfillment/database/migrations`
- `PromotionServiceProvider` → `domains/promotion/database/migrations`
- `ShopCoreServiceProvider` → `domains/shop-core/database/migrations`

---

## 7. Migration file class naming convention

Laravel identifies migration classes by their filename. Use the convention:

```
{timestamp}_{action}_{table_name}_table.php
```

The anonymous migration class syntax (`return new class extends Migration`) is
preferred (already used in this project). Do not use named classes — they will
conflict across domains if the same table name appears in multiple files.

Example:
```php
// 2026_04_13_120001_create_shop_currencies_table.php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_currencies');
    }
};
```

---

## 8. Execution steps

### Step 1 — Verify ServiceProvider `loadMigrationsFrom` calls

Confirm each domain's ServiceProvider has `$this->loadMigrationsFrom(...)` in
`boot()`. Run:

```bash
grep -r "loadMigrationsFrom" domains/ --include="*.php"
```

If any domain is missing this call, add it before running migrations.

### Step 2 — Create the new per-table migration files

For each file listed in section 4, create it in the domain's
`database/migrations/` directory. Copy the relevant `Schema::create` or
`Schema::table` block from the existing consolidated migration files.

Source files to decompose:
- `database/migrations/2026_04_13_120000_create_sylius_shop_core_tables.php`
- `database/migrations/2026_04_13_130000_create_shop_customer_and_addressing_tables.php`
- `database/migrations/2026_04_13_140000_create_shop_order_tables.php`
- `database/migrations/2026_04_13_150000_add_inventory_and_channel_pricing.php`
- `database/migrations/2026_04_13_160000_shop_taxation_shipping_payment.php`
- `database/migrations/2026_04_13_170000_shop_taxonomy_associations_promotions.php`
- `database/migrations/2026_04_14_100000_shop_product_options_and_attributes.php`
- `database/migrations/2026_04_14_110000_shop_product_images.php`
- `database/migrations/2026_04_14_140000_shop_extras_reviews_contact.php`

### Step 3 — Remove the old consolidated migration files

Once all new files are in place and verified, delete the 9 consolidated files
from `database/migrations/`. Do NOT delete the 5 framework files listed in §4.8.

### Step 4 — Reset and re-run in a fresh environment

```bash
php artisan migrate:fresh
```

Confirm: no errors, all tables exist, FK constraints are valid.

### Step 5 — Validate table creation order in production-like flow

```bash
php artisan migrate:status
```

Confirm the run order matches the dependency graph in section 3. The batch numbers
in the output reflect the order Laravel executed them — cross-check against the
cheat-sheet in section 5.

### Step 6 — Regenerate IDE helpers (optional but recommended)

```bash
php artisan ide-helper:models --nowrite
```

---

## 9. Rollback safety

Every migration file must implement `down()` that reverses only what its own `up()`
did. For ALTER migrations, `down()` drops the added columns/FKs only.

For the circular FK (`shop_customers` ↔ `shop_addresses`), rollback order must be:
1. Drop `default_address_id` FK from `shop_customers` (file `130004`)
2. Drop `shop_addresses` (file `130003`)
3. Drop `shop_customers` (file `130002`)

Laravel's `migrate:rollback` processes in reverse batch order, which naturally
handles this if the files were run in the correct order.

---

## 10. Done criteria

- [ ] All 9 consolidated migration files are deleted from `database/migrations/`.
- [ ] Every domain has its own `database/migrations/` directory populated with
      per-table files.
- [ ] All 7 domain ServiceProviders register their migration paths in `boot()`.
- [ ] `php artisan migrate:fresh` succeeds with no errors.
- [ ] `php artisan migrate:status` shows every file and correct batch ordering.
- [ ] Rollback of each domain's migrations leaves no orphan FK violations.
- [ ] No cross-domain timestamp ordering conflicts remain (see §5 cheat-sheet).
