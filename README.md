# Sylius Laravel Port

Laravel port of the Sylius e-commerce domain, implemented as a modular monolith with domain packages.

## Application Design

This project follows a domain-first design:

- The root Laravel app is the composition layer (HTTP entrypoints, global config, shared runtime).
- Business capabilities are split into isolated domain packages under `domains/`.
- Each domain is loaded as a local Composer path package (`repositories: domains/*`), then auto-registered via service providers.
- Domains keep their own routes, migrations, models, resources, and tests to preserve bounded contexts and reduce coupling.

## Domain Modules

Current modules live in `domains/` and mirror the repository structure:

- `shop-core`: channels, locales, currencies, countries, zones, tax and shipping categories.
- `catalog`: products, variants, taxons, options, attributes, pricing, images, reviews, and translations.
- `customer`: customers, addresses, and customer groups.
- `checkout`: orders, order items/units, adjustments, payment methods, and payments.
- `fulfillment`: shipping methods, shipments, and shipment units.
- `promotion`: promotions, rules, actions, channel assignment, and coupons.
- `content`: storefront contact and content-oriented models.
- `reporting`: reporting-focused domain capabilities.

Reference: [domains folder on GitHub](https://github.com/daudmabena/sylius_laravel/tree/main/domains)

## Module Layout Convention

Each domain follows the same package structure:

```text
domains/<module>/
  composer.json
  src/
  routes/
  database/
  resources/
  tests/
```

This keeps domain code cohesive and makes incremental migration from the Symfony Sylius codebase easier to manage.

## Getting Started

### Requirements

- PHP 8.3+
- Composer
- Node.js + npm

### Setup

```bash
composer setup
```

This runs install, environment bootstrap, key generation, database migrations, and frontend build.

### Development

```bash
composer dev
```

Starts the full local stack (web server, queue worker, logs, and Vite).

### Tests

```bash
composer test
```

## Contribution Notes

- Prefer adding behavior in the appropriate domain package rather than the root app.
- Keep domain boundaries explicit; cross-domain dependencies should be intentional and minimal.
- Add or update tests in the touched domain package.
