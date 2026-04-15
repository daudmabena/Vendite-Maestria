# 03 — Domain and Application Re-Engineering

## 1. Domain facts and aggregate map

### Primary aggregates

- `Order` aggregate: `Order`, `OrderItem`, `OrderItemUnit`, `Adjustment`, coupon link, shipment/payment links.
- `Product` aggregate: `Product`, `ProductVariant`, option/value, attribute/value, image, association links.
- `Customer` aggregate: `Customer`, addresses, owned orders, reviews.

### Supporting domains

- Pricing/context: Channel, ChannelPricing, Currency, Locale, CustomerGroup.
- Fiscal: TaxCategory, TaxRate, tax calculator.
- Discounting: Promotion with rules/actions/coupons.
- Fulfillment: Shipment, ShipmentUnit, ShippingMethod, ShippingCategory.
- Identity/access: User, Sanctum tokens, policies, admin middleware.

## 2. Application flow arrangement

### Command flows (existing)

- Cart: create, add item, update item quantity, remove item.
- Pricing modifiers: apply/remove coupon, set shipping method.
- Checkout: create payment/shipment records, reserve stock, transition states, emit notifications.
- Moderation/operations: review status handling, contact message status handling.

### Flow ownership

- `app/Http`: validation + authorization + delegation.
- `app/Shop`: command orchestration and domain invariants.
- `app/Repositories`: query/persistence.
- `app/Notifications`: outbound side effects.

## 3. Re-engineering actions by domain area

### Order/cart

- Enforce workflow state guard before every mutation.
- Keep money recalculation centralized in totals refresher.
- Keep stock sufficiency and reservation deterministic.

### Payment/shipment

- Keep gateway/driver selection in payment service layer.
- Keep transition authorization in workflow services.
- Keep shipment progression tied to explicit state paths.

### Promotion/tax/shipping calculations

- Keep adjustment creation centralized in dedicated calculators/applicators.
- Keep ordering and exclusivity semantics deterministic.

### Product configuration/media

- Keep variant-option and attribute-value semantics separate.
- Keep image positioning and ownership rules consistent.

### Review/contact extras

- Keep submission/moderation lifecycle explicit (pending/new -> processed states).
- Keep customer/admin visibility contracts separate.

## 4. Code arrangement rules inside `app/Shop`

- `Cart/*`: cart and checkout command orchestrators + totals refresh.
- `Workflow/*`: all order/payment/shipment state transitions.
- `Payment/*`: gateway driver dispatch and begin/complete/fail handling.
- `Promotion/*`: rule checks + action application into adjustments.
- `Taxation/*` and `Shipping/*`: deterministic calculators.
- `Context/*` and `Pricing/*`: request/channel/customer-group context resolvers.
- `Order/*`: order identifier generation and order-specific helper services.

## 5. Domain invariants catalog (must be enforced)

- Only cart-state orders can accept cart mutations.
- Tracked variants cannot exceed available stock constraints.
- Coupon applicability must satisfy validity + promotion gate.
- Customer-owned resources are only accessible by owning identity.
- Admin-only operations require admin context.
- Payment/shipment/order transitions must follow allowed matrix only.

## 6. Done criteria

- Every domain invariant maps to one owner (service/workflow/policy).
- No critical rule duplication across layers.
- Core command flows are deterministic and testable in isolation.