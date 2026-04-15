# 01 — System Baseline and Gap Analysis

## 1. Current system baseline

`sylius_laravel` is a functional modular monolith with a broad eCommerce domain and working command flows (cart, checkout, payment, shipment, moderation, contact).

## 2. Domain facts (as-is)

### Catalog and merchandising

- Product + ProductVariant are core sellable entities.
- ProductOption/ProductOptionValue model configurable variants.
- ProductAttribute/ProductAttributeValue model descriptive specs.
- ProductImage supports product and variant media.
- Taxon models taxonomy and product classification.
- ProductAssociation/ProductAssociationType supports cross-sell/related links.

### Customer and identity

- User + Customer split auth identity from commerce profile.
- Address is customer-owned and policy-protected.
- Sanctum is active for token-based API access.

### Order and checkout

- Order is cart and checkout aggregate root.
- OrderItem and OrderItemUnit track line quantities and unit-level adjustments.
- Adjustment is canonical monetary delta model (promotion/shipping/tax/etc).
- Order number/token generation is centralized.

### Payment and shipping

- Payment + PaymentMethod support manual and Stripe driver orchestration.
- Shipment + ShippingMethod + ShipmentUnit capture fulfillment lifecycle.
- Order/Payment/Shipment workflows use enums + transition services.

### Pricing, tax, promotion

- Channel pricing resolves per-channel price context.
- TaxCategory/TaxRate + tax calculator provide tax adjustments.
- Promotion/PromotionRule/PromotionAction/PromotionCoupon provide discount engine.

### Context, extras, and communication

- Channel/Locale/Currency are request-context and pricing primitives.
- ProductReview is customer-submitted and admin-moderated.
- ContactMessage supports public intake and admin handling.
- Notifications exist for order confirmation and shipment shipped events.

## 3. Code arrangement (as-is)

- `app/Models/Shop`: entity schema, relationships, lightweight model behavior.
- `app/Shop/Cart|Payment|Promotion|Taxation|Shipping|Workflow|Context|Pricing|Order`: core business orchestration.
- `app/Repositories/Contracts/Shop` + `app/Repositories/Shop`: CRUD-oriented persistence abstraction.
- `app/Http/Controllers/Api/V1/Shop`: transport endpoints (mixed command/resource style).
- `app/Http/Requests/Api/V1/Shop`: validation entry points.
- `app/Http/Resources/Shop`: partial response normalization.
- `app/Policies/Shop` + middleware (`EnsureAdmin`, `ResolveShopContext`): access + context cross-cutting.
- `routes/api.php`: centralized endpoint matrix (public/auth/customer/admin).

## 4. Gap map

### Separation gaps

- Controller consistency varies; some actions remain CRUD-shaped while domain is command-shaped.
- Business rules are not fully single-sourced across services/policies/requests.

### Contract gaps

- API response envelopes and error mappings are not fully uniform.
- Repository contracts are broad but not consistently semantic.

### Modularity gaps

- `CartService` aggregates many responsibilities and has high change blast radius.
- Route and endpoint governance is centralized but dense.

### Governance gaps

- Generator overwrite risk can regress hardened logic.
- Architecture guardrails are not yet CI-enforced.

## 5. Baseline risk priorities

- P1: security/ownership regressions due to generated overwrite.
- P1: duplicate domain rules causing inconsistent outcomes.
- P2: contract drift for API consumers.
- P2: performance hotspots in totals/promotion/checkout paths.
- P3: operational side-effect coupling and retry ambiguity.

## 6. Re-engineering baseline objective

Preserve current domain scope and endpoint surface while enforcing strict boundary ownership, stable contracts, and scalable runtime behavior.