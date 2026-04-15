# 06 — Performance, Reliability, and Operations Plan

## 1. Domain facts for performance focus

- Most performance-sensitive write path: cart -> checkout -> payment/shipment.
- Most performance-sensitive read path: catalog/product/variant/taxonomy with channel pricing context.
- High side-effect sensitivity: order confirmation, shipment notification, payment outcomes.
- Race-prone area: tracked inventory in concurrent cart/checkout flows.

## 2. Code arrangement for performance controls

- Query optimization ownership: repositories + service orchestrators.
- N+1 prevention ownership: repository load profiles and resource usage discipline.
- Write-path latency ownership: `app/Shop/Cart`, `app/Shop/Workflow`, `app/Shop/Payment`.
- Side-effect latency ownership: notification dispatch strategy and queue runtime.

## 3. Performance checklist by domain path

### Catalog

- Define standard eager-loading profiles for product list/detail paths.
- Define pagination and filter defaults by endpoint category.
- Keep channel pricing resolution bounded and cache-aware.

### Cart/checkout

- Keep totals/promotion/tax recomputation efficient and scoped.
- Minimize transaction lock scope for order and stock updates.
- Ensure repeated checkout calls are idempotent and fast-fail on invalid state.

### Admin operations

- Ensure admin list endpoints use bounded filters and page sizes.
- Prevent expensive unrestricted queries on large entities.

## 4. Reliability and failure model

- Workflow failures must preserve state machine integrity.
- Side-effect failures (mail/queue) must not corrupt committed business state.
- Payment gateway failures must map to deterministic payment transition outcomes.
- Queue outage behavior must be observable and recoverable.

## 5. Observability arrangement

- Logs: structured by request and order/cart identifiers.
- Metrics: latency, error rate, queue health, checkout conversion, stock conflict rate.
- Alerts: payment failure spikes, queue backlog growth, checkout error ratio, authz failures.

## 6. Deployment and runbook arrangement

- Deployment safety owner: migration compatibility + release sequencing.
- Runtime safety owner: queue workers, notification channel health, rollback readiness.
- Incident response owner: defined on-call actions for payment/checkout and auth incidents.

## 7. Done criteria

- Performance budgets exist per critical flow.
- Reliability behavior is documented for primary failure modes.
- Operations teams have actionable dashboards and runbooks.