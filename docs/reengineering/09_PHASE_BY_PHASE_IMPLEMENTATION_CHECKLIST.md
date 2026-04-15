# 09 — Phase-by-Phase Implementation Checklist

Use this checklist to execute the re-engineering while preserving current domain scope.

## A. Domain facts checklist (must be completed first)

- Confirm aggregate ownership map for Product, Customer, and Order domains.
- Confirm workflow matrix for order/payment/shipment transitions.
- Confirm invariants for stock, totals, coupon eligibility, and ownership access.
- Confirm route access classes (public/customer/admin).
- Confirm side-effect map (notifications and operational status changes).

## B. Code arrangement checklist (global)

- `app/Models/Shop` contains entity persistence/relations only.
- `app/Shop/*` contains orchestration/workflow/calculator logic.
- `app/Repositories/*` contains reusable query and persistence semantics.
- `app/Http/*` contains transport validation/authorization/serialization only.
- `app/Policies/*` and middleware contain access/context rules only.
- `app/Notifications/*` contains outbound communication payload logic only.

---

## Phase 0 — Baseline

- Produce domain fact registry document.
- Produce code arrangement ownership matrix.
- Snapshot key API contracts and error shapes.
- Activate generator change-control for protected files.

## Phase 1 — Boundary enforcement

- Normalize controllers to thin adapter pattern.
- Remove business orchestration from transport layer.
- Standardize service method contracts (input/precondition/side-effect/error).
- Add dependency direction checks in CI.

## Phase 2 — Domain consolidation

- Route all state mutations through workflow services.
- Centralize cart/checkout domain invariants.
- Eliminate duplicate business rules across layers.
- Ensure idempotent behavior for critical mutation commands.

## Phase 3 — Data and events

- Add semantic query methods to repositories where repeated.
- Standardize transaction and post-commit side-effect patterns.
- Define event points for lifecycle facts.
- Define queue retry/backoff/failure handling expectations.

## Phase 4 — Interface and security

- Standardize response envelopes for resources and commands.
- Standardize domain/auth/validation error envelopes.
- Revalidate endpoint access matrix and policy coverage.
- Tighten request validation for public write endpoints.

## Phase 5 — Performance and operations

- Define and monitor latency/query budgets for top endpoints.
- Remove high-impact N+1 and over-fetching paths.
- Add observability for checkout, auth, queue, and failures.
- Validate deployment/rollback runbooks in staging.

## Phase 6 — Governance

- Enforce architecture checklist in all PRs.
- Enforce contract regression checks in CI.
- Maintain ADR and rule-catalog updates per major change.
- Run periodic architecture fitness reviews.

---

## Exit sign-off template (each phase)

- Architecture sign-off complete.
- Security sign-off complete.
- QA sign-off complete.
- Operations sign-off complete.

## Program done

- Domain facts are explicit and test-protected.
- Code arrangement is documented, enforced, and followed.
- Modularity, maintainability, and scalability goals are achieved without adding new modules.