# 07 — Testing, Quality, and Governance Plan

## 1. Domain facts that testing must protect

- Customer ownership boundaries (orders/addresses/reviews).
- Admin protection boundaries (write/moderation operations).
- Workflow legality across order/payment/shipment transitions.
- Cart invariants (stock sufficiency, totals consistency, coupon validity).
- Context resolution invariants (channel/locale precedence).
- Contract stability for command and resource endpoints.

## 2. Test code arrangement

- `tests/Feature/Shop`: end-to-end API and access matrix behavior.
- `tests/Feature` for workflow-integrated service behavior requiring DB state.
- Additional layer-focused tests organized by domain capability (cart/workflow/security/contracts).

## 3. Layered testing strategy

### Domain/workflow

- Transition matrix tests for order/payment/shipment.
- Invariant tests for invalid state transitions.

### Application orchestration

- Checkout/cart orchestration tests with inventory and totals assertions.
- Side-effect dispatch assertions for notifications and queued behavior.

### Interface/security

- Route access matrix tests (public/customer/admin).
- Ownership policy enforcement tests for customer-owned resources.
- Error-shape tests for validation/authz/domain failures.

### Contract regression

- Response shape snapshot tests for core endpoints.
- Compatibility checks when resources/requests evolve.

## 4. Governance arrangement

- Architecture guardrails enforced in CI.
- Generator-sensitive files protected by review policy.
- Required review checklist: boundary compliance, security correctness, contract impact, performance impact.
- ADR and rule-catalog updates required for significant design changes.

## 5. Quality gates

- Formatting and static checks.
- Required test suites by layer.
- Architecture dependency checks.
- Contract regression checks.

## 6. Done criteria

- Critical domain facts are covered by automated tests.
- Governance controls prevent boundary/security regressions.
- CI gates block non-compliant architectural drift.