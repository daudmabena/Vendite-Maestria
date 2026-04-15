# 05 — Interface, Security, and API Contract Plan

## 1. Domain facts that drive interface/security

- Public domain facts: catalog browsing, channel/locale context, contact submission.
- Authenticated customer facts: profile, addresses, owned orders, cart commands, review submission.
- Admin facts: protected write operations across shop resources and moderation endpoints.
- Workflow facts: invalid transitions must become deterministic client errors.

## 2. API code arrangement

- `routes/api.php`: route grouping and access class boundaries.
- `app/Http/Controllers/Api/V1/Shop`: endpoint handlers only.
- `app/Http/Requests/Api/V1/Shop`: request validation + authorization intent.
- `app/Http/Resources/Shop`: output contract normalization.
- `app/Policies/Shop` + middleware: ownership/admin/context enforcement.

## 3. Contract standardization plan

### Success responses

- Resource `index/show`: consistent resource envelopes.
- Command actions (cart/checkout/auth): consistent command result envelopes.
- Admin mutation responses: same shape conventions as resource actions.

### Error responses

- Validation errors: uniform field-error schema.
- Authn/authz errors: consistent status and payload style.
- Domain/workflow conflicts: clear semantic error class and message policy.
- Not-found and state conflict errors: deterministic mapping.

## 4. Security arrangement and controls

### Authn

- Sanctum remains canonical token auth.
- Token lifecycle policy documented for register/login/logout and revocation.

### Authz

- Ownership checks in policies for customer-owned models.
- Role checks in admin middleware/policies.
- Controller authorization calls standardized per action type.

### Input and abuse hardening

- Public write endpoints receive strict validation and throttling profile.
- Server-owned fields are stripped/ignored from client payloads.
- Sensitive fields are never emitted through resources by default.

## 5. Route grouping facts and expectations

- Public routes: read-only catalog + auth register/login + contact create.
- Customer-auth routes: cart commands + own resources + review create.
- Admin-auth routes: create/update/delete on protected resources + moderation/control routes.

## 6. Compatibility and version governance

- `v1` remains stable; contract-breaking changes require explicit versioning strategy.
- Any contract adjustment requires compatibility note and test updates.

## 7. Done criteria

- Interface patterns are uniform across endpoints.
- Security model is coherent and test-verified.
- API consumers can rely on stable, documented contracts.