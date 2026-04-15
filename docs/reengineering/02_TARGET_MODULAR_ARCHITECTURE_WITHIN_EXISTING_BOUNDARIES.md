# 02 — Target Modular Architecture Within Existing Boundaries

## 1. Target architecture style

Target: clean modular monolith, preserving existing boundaries and domain scope.

## 2. Domain facts that shape architecture

- `Order` remains aggregate root for cart-to-checkout lifecycle.
- `Payment` and `Shipment` remain workflow-driven satellites tied to order lifecycle.
- `Adjustment` remains canonical accounting mechanism for all total deltas.
- `Channel` and `Locale` remain request-context anchors.
- `Promotion` and `Tax` stacks remain pluggable by rule/action/calculator services.
- `Product` domain remains split between sellable variants and descriptive metadata (options/attributes/images/associations).
- Reviews and contact stay inside existing shop application scope (not extracted).

## 3. Code arrangement target (folder-level)

### `app/Models/Shop`

- Keep persistence and relations only.
- Keep casts/constants and primitive guards.
- No orchestration or transport coupling.

### `app/Shop/*`

- Keep all business workflows and use-case orchestration.
- Split large services internally by use-case step responsibilities.
- Own transaction boundaries and domain exception semantics.

### `app/Repositories/*`

- Own query composition and persistence semantics.
- Expose reusable business-read methods beyond base CRUD where necessary.
- Avoid transport/data-shape concerns.

### `app/Http/*`

- Own route-to-use-case mapping, validation, policy/middleware enforcement.
- Keep controller actions thin and deterministic.
- Ensure resources provide canonical response contracts.

### `app/Policies/*` and `app/Http/Middleware/*`

- Own access matrix and request context resolution only.

### `app/Notifications/*`

- Own outbound message construction and channel strategy.

## 4. Dependency direction rules

Allowed:

- `Http -> Shop -> Repositories -> Models`
- `Shop -> Models`
- `Shop -> Notifications`
- `Http -> Policies/Middleware`

Disallowed:

- `Models -> Http`
- `Repositories -> Http`
- `Policies -> domain write orchestration`
- `Controllers -> complex query/business orchestration`

## 5. Internal contract rules

### Service contracts

Every `app/Shop` public method must define:

- input contract,
- preconditions,
- state transitions,
- side effects,
- error outcomes,
- idempotency expectations.

### Repository contracts

Every repository should declare:

- canonical list/read query methods,
- ownership-scoped retrieval methods where needed,
- pagination/filter defaults.

### API contracts

Every endpoint class should map to one response style:

- command response shape,
- resource item shape,
- resource collection shape,
- standardized error shape.

## 6. Arrangement of route surface (same module scope)

Inside existing `v1/shop` surface, keep explicit groups:

- public read/catalog
- public auth/contact
- authenticated customer command endpoints
- authenticated customer owned resources
- admin-only write/moderation endpoints

## 7. Success state

Architecture is complete when domain rules are single-sourced in `app/Shop`, transport is thin/consistent, persistence is semantic, and contracts are stable.