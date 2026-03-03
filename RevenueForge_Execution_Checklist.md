# RevenueForge - Quick Execution Checklist

## Mission
Build a premium SaaS MVP called **RevenueForge**:

**Workspace -> AI Offer -> Mayar Checkout -> Webhook -> Access Unlock -> Analytics**

---

## Stage 1 - Product Lock
- Lock product positioning
- Lock personas
- Lock MVP scope
- Lock non-goals
- Lock demo story
- Lock UI direction

## Stage 2 - Execution Blueprint
- Final feature list
- User roles
- Full user flows
- Dashboard metrics
- Integration strategy
- 7-day build sequence

## Stage 3 - Architecture Lock
- Folder structure
- Route map
- DB schema
- Models and relationships
- Env keys
- Module boundaries
- State machine
- Seed plan

## Stage 4 - Implementation Blueprint
- Migrations
- Models
- Controllers
- Requests
- Services
- Actions
- Jobs
- Webhook pipeline
- Dashboard query layer
- UI page order
- Tests

---

## Golden Path
1. Workspace owner signs in
2. Creates offer with AI
3. Publishes offer
4. Buyer pays via Mayar
5. Webhook confirms payment
6. Access or credits unlock
7. Customer portal trigger works
8. Dashboard updates

---

## Core Modules
- Auth and Workspace
- AI Offer Studio
- Offer Manager
- Billing and Mayar Integration
- Webhook Automation Engine
- Customer CRM
- Credit Wallet
- Affiliate Tracking
- Revenue Dashboard
- Settings and Branding

---

## Offer Types
- one-time
- subscription
- credit pack
- saas plan

---

## Payment Rules
- Always create local order before redirect
- Always create local payment before redirect
- Never unlock from success page alone
- Unlock only after webhook or reconciliation
- Every webhook must be idempotent
- Every provider event must be logged

---

## Priority Build Order
1. foundation
2. offers + AI
3. checkout + payments
4. webhook automation
5. customer access
6. analytics
7. affiliates
8. polish + tests

---

## Must-Have Pages
- landing
- pricing
- dashboard
- offers list
- offer create
- offer edit
- public offer page
- billing
- customers
- customer detail
- affiliates
- analytics
- success page
- cancel page

---

## Must-Have Services
- OfferGeneratorService
- MayarClient
- MayarPaymentService
- MayarCustomerService
- MayarMembershipService
- MayarCreditService
- MayarPortalService
- AffiliateAttributionService
- RevenueSummaryQuery

---

## Must-Have Actions
- GenerateOfferAction
- CreateOfferCheckoutAction
- CreateWorkspaceCheckoutAction
- ProcessWebhookEventAction
- GrantOfferAccessAction
- AddCreditsAction
- SendPortalMagicLinkAction
- RecordAffiliateConversionAction

---

## Must-Have Jobs
- ProcessMayarWebhookJob
- SyncMayarPaymentStatusJob
- RetryFailedWebhookJob
- RebuildDailyMetricsJob

---

## UX Rules
- premium SaaS feel
- mobile-first
- clean cards and tables
- clear status badges
- strong empty states
- fast scanning dashboards

---

## Final Reminder
Protect the golden path.
Keep Mayar central.
Keep AI useful.
Keep the dashboard believable.
Ship a coherent demo-ready SaaS product.
