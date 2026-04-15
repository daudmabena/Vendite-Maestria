# sylius_laravel Re-Engineering Plan Index

This document pack is the authoritative re-engineering guide for `sylius_laravel`.

## Scope constraints

- Work strictly within existing module boundaries.
- No new business modules are introduced.
- The plan is architecture-first and implementation-oriented.
- No code snippets are included.

## Domain facts covered across all documents

- Core shop aggregates already present: Channel, Locale, Currency, Product, ProductVariant, Taxon, Customer, Address, Order, OrderItem, OrderItemUnit, Adjustment, Payment, PaymentMethod, Shipment, ShippingMethod, TaxCategory, TaxRate, Promotion (+ rules/actions/coupons), ProductAssociation (+ type), Zone (+ members), Country, Province.
- Extended commerce entities already present: ProductOption, ProductOptionValue, ProductAttribute, ProductAttributeValue, ProductImage, ProductReview, ContactMessage.
- Workflow state machines already present: order, payment, shipment via enums + workflow services.
- Context and cross-cutting already present: shop channel/locale resolution, pricing context, order token/number generation, inventory reservation, notifications.

## Code arrangement baseline

- `app/Models/Shop`: entity + relationship layer.
- `app/Shop/*`: domain logic and application orchestration.
- `app/Repositories/*`: persistence abstraction.
- `app/Http/*`: API transport, validation, resources, middleware.
- `app/Policies/*`: ownership/authorization rules.
- `app/Notifications/*`: outbound user/admin communications.
- `app/Providers/*`: dependency and policy wiring.
- `routes/api.php`: endpoint surface organization.

## Documents

1. `01_SYSTEM_BASELINE_AND_GAP_ANALYSIS.md`
2. `02_TARGET_MODULAR_ARCHITECTURE_WITHIN_EXISTING_BOUNDARIES.md`
3. `03_DOMAIN_AND_APPLICATION_REENGINEERING.md`
4. `04_INFRASTRUCTURE_DATA_AND_EVENT_ARCHITECTURE.md`
5. `05_INTERFACE_SECURITY_AND_API_CONTRACT_PLAN.md`
6. `06_PERFORMANCE_RELIABILITY_AND_OPERATIONS_PLAN.md`
7. `07_TESTING_QUALITY_AND_GOVERNANCE_PLAN.md`
8. `08_PHASED_EXECUTION_ROADMAP.md`
9. `09_PHASE_BY_PHASE_IMPLEMENTATION_CHECKLIST.md`

## Reading order

Read in order. Each document adds depth while preserving the same domain facts and code arrangement rules.