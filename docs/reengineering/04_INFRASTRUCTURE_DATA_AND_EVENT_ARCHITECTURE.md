# 04 — Infrastructure, Data, and Event Architecture

## 1. Domain facts relevant to infrastructure

- High-write paths center around cart/checkout/order/payment/shipment.
- High-read paths center around product/catalog/taxonomy/channel pricing.
- Moderation and support flows (review/contact) are operationally sensitive but lower throughput.
- Token and identity management is central for customer/admin API access.

## 2. Data arrangement and persistence ownership

### Data model arrangement

- Commerce core tables remain grouped by existing migration families.
- Newer additions (options/attributes/images/reviews/contact/auth/admin) remain in current migration lineage.
- No schema-level module split is introduced; governance is by naming and ownership conventions.

### Repository arrangement

- `app/Repositories/Contracts/Shop/*Interface.php`: authoritative query/write contract.
- `app/Repositories/Shop/*Repository.php`: implementation and query optimization point.
- `AbstractShopRepository`: base mechanics only; domain semantics live in concrete repos.

### Query ownership rules

- Controllers do not own reusable complex queries.
- Services and repositories coordinate read/write orchestration.
- Ownership-scoped query patterns are reusable methods, not repeated inline fragments.

## 3. Event and side-effect arrangement

### Event fact map

Key lifecycle facts that must trigger events/side effects:

- order placed,
- payment completed/failed,
- shipment shipped,
- review moderated,
- contact status changed,
- inventory reserved/released.

### Placement rules

- Domain/application services determine when an event-worthy fact occurs.
- Notification classes build delivery payloads.
- Queue runtime handles deferred processing and retries.

## 4. Transaction and consistency model

- Transaction boundaries are declared in service layer (`app/Shop/*`).
- Side effects that depend on committed state are post-commit.
- Partial failures cannot corrupt order/payment/shipment invariants.
- Conflict handling for stock-sensitive updates is explicit and documented.

## 5. Migration and lifecycle governance

- Every migration batch includes compatibility and rollback notes.
- Unique identifiers (codes/tokens/numbers) have explicit integrity guarantees.
- FK and ownership constraints remain strict for customer/order/address/review relations.
- Data retention strategy is defined for non-core operational data.

## 6. Operational infrastructure arrangement

- API runtime: `routes/api.php` + controllers/middleware.
- Queue runtime: configured worker process with retries/timeouts.
- Notification runtime: mail/other channels through notification system.
- Logging/monitoring: structured around command and workflow transitions.

## 7. Done criteria

- Repository contracts are semantic and reused.
- Event/side-effect flows are observable and recoverable.
- Data integrity and migration safety are documented and enforced.