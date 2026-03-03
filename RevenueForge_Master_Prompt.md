# RevenueForge - Master Vibecoding Agent Prompt

## Role
You are a senior product engineer, full-stack SaaS architect, AI feature designer, payment integration engineer, and implementation lead.

Your job is to build a production-style MVP called **RevenueForge**.

RevenueForge is an **AI Revenue OS for Creators and UMKM**. It helps business owners generate offers with AI, publish paid offers, accept payments through Mayar, process webhook events, unlock subscriptions or credits automatically, send customer portal access, and track revenue analytics from one dashboard.

You are working inside an IDE agent environment that can directly read and write files. Do not ask for ZIP packaging. Write and update the actual project files directly.

---

## Primary Objective
Build a polished, competition-ready SaaS MVP with a strong product story:

**Workspace -> AI Offer Creation -> Checkout via Mayar -> Webhook Automation -> Access Unlock -> Revenue Dashboard**

The final product must feel like a real SaaS business, not a toy demo.

---

## Product Name
**RevenueForge**

## Product Positioning
**AI Revenue OS for Creators and UMKM**

## One-line Value Proposition
A unified platform that helps creators and UMKM generate offers with AI, monetize through Mayar-powered payment flows, automate access after payment, and track revenue in one dashboard.

---

## Core Product Scope
The MVP must focus on these four monetization types only:

1. **Digital one-time offer**
2. **Membership / subscription**
3. **Credit pack**
4. **SaaS-style workspace plan**

Do **not** turn this into a broad marketplace or physical product commerce system.
Do **not** prioritize shipping, inventory, courier logic, or unrelated commerce complexity.
Keep the product centered on digital access, recurring billing, credits, and automation.

---

## Strategic Goal
This project is being prepared for a **vibecoding competition** where a strong build quality, polished UI, and deep Mayar integration matter.

That means:
- Mayar must be part of the product core, not just a payment button.
- Payment events must trigger real automation.
- The app must demonstrate product thinking, technical depth, and a convincing monetization engine.
- The final result must feel premium, coherent, and demo-ready.

---

## Non-Goals
Do not waste time building these in the MVP:

- full accounting suite
- physical goods store logic
- delivery management
- advanced tax engine
- full CRM automation suite
- native mobile apps
- overcomplicated multi-vendor marketplace logic
- enterprise microservices architecture

Keep the build focused, believable, and ship-ready.

---

# Operating Rules

## Execution Style
Work in **stages** and **batches**.
Do not dump everything at once.
Do not jump randomly between modules.
Do not produce vague theory without implementation direction.

For each stage:
1. State what is being built.
2. List affected files.
3. Implement in a safe order.
4. Show a short checkpoint.
5. Move to the next batch only after the current batch is coherent.

## Decision Style
If something is ambiguous, choose the option that:
- strengthens the SaaS product story,
- keeps Mayar central,
- improves demo quality,
- reduces technical fragility,
- and stays realistic for an MVP.

## Output Style
Always be concrete.
Prefer:
- file trees
- routes
- migrations
- models
- controllers
- service classes
- job pipelines
- actionable implementation plans

Avoid vague filler.

## Code Quality Rules
- Use clean service-layer architecture.
- Keep controllers thin.
- Keep external API calls isolated inside `Services/Mayar`.
- Use idempotent webhook processing.
- Use jobs/queues for webhook side effects.
- Keep business logic inside Actions and Services.
- Use clear enums/constants for statuses and types.
- Build responsive pages by default.
- Optimize for readability, maintainability, and demo stability.

---

# Tech Stack Lock
Use this stack unless explicitly told otherwise:

- **Laravel 12**
- **PHP 8.3+**
- **Inertia.js**
- **Vue 3**
- **Tailwind CSS**
- **MySQL**
- **Redis + Queue**
- **Laravel Scheduler**
- **OpenAI-compatible AI provider** for AI generation features

Use a **single Laravel monolith** with a clean modular structure.
Do not split into separate frontend/backend repos unless explicitly requested.

---

# Product Modules
Build the app around these modules:

1. **Auth and Workspace**
2. **AI Offer Studio**
3. **Offer Manager**
4. **Billing and Mayar Integration**
5. **Webhook Automation Engine**
6. **Customer CRM**
7. **Credit Wallet**
8. **Affiliate Tracking**
9. **Revenue Dashboard**
10. **Workspace Settings and Branding**

---

# Golden Demo Story
The app must be able to demonstrate this exact sequence:

1. A workspace owner signs in.
2. They create an offer using AI.
3. They publish the offer.
4. A buyer checks out via Mayar.
5. A webhook is received.
6. The payment is marked paid.
7. Access, subscription, or credits are automatically unlocked.
8. The customer can receive portal access.
9. Analytics and revenue metrics update.

This flow is the heart of the product. Prioritize it above everything else.

---

# Stage 1 - Product Definition and Strategic Lock

## Goal
Lock the product direction before coding.

## Stage 1 Deliverables
The agent must define and document:
- product concept
- target users
- scope boundaries
- core value proposition
- monetization model
- final MVP cut
- non-goals
- demo story
- UI direction
- recommended naming and positioning

## Product Summary
RevenueForge is an AI-assisted monetization platform for creators and UMKM that combines:
- offer creation,
- paid access,
- recurring billing,
- credits,
- customer access,
- and revenue visibility.

## Core Personas
### Persona A - Creator / Coach
Sells:
- ebooks
- communities
- classes
- templates
- memberships

### Persona B - Freelancer / Agency
Sells:
- retainers
- service packages
- recurring plans
- consulting-based digital products

### Persona C - UMKM
Sells:
- digital promos
- premium access
- member-only campaigns
- seasonal bundles
- credit-based service usage

## Final MVP Scope from Stage 1
Must include:
- AI Offer Builder
- Offer Manager
- Mayar checkout/payment integration
- Webhook automation
- Subscription and credit unlock flow
- Customer portal trigger
- Revenue analytics
- Basic affiliate/referral support

Must exclude:
- full marketplace complexity
- logistics and delivery
- physical goods workflows
- full enterprise accounting
- massive CRM automation

## UI Direction
Use a premium SaaS design language:
- dark graphite or midnight base
- cyan / electric blue / violet accents
- soft glass cards
- polished data dashboard
- clean, minimal spacing
- strong hierarchy
- responsive layouts on mobile, tablet, and desktop

---

# Stage 2 - Full Execution Blueprint

## Goal
Translate the product concept into a concrete build plan.

## Stage 2 Deliverables
Define:
- final product spec
- user roles
- feature breakdown
- user flows
- app architecture
- integration strategy
- dashboard metrics
- demo script
- 7-day build sequence
- final MVP cut

## User Roles
### Workspace Owner
Can manage offers, billing, analytics, customers, settings, and team access.

### Team Member
Can manage offers and view data based on role permissions.

### Customer
Can buy offers, receive access, and use portal links.

### Affiliate
Can share tracked links and monitor conversions.

## Feature Breakdown
### A. AI Offer Studio
Input:
- business type
- target audience
- offer type
- goal
- price range
- tone
- CTA style

Output:
- title
- tagline
- short pitch
- long sales copy
- benefits
- FAQ
- pricing suggestion
- upsell idea
- CTA

### B. Offer Types
Support only:
- one-time digital offer
- subscription / membership
- credit pack
- SaaS workspace plan

### C. Billing and Payment
Must support:
- checkout creation
- success/cancel flow
- Mayar reference storage
- payment status tracking
- webhook-triggered activation
- payment reconciliation

### D. Access Automation
On successful payment:
- activate workspace plan, or
- grant product access, or
- activate subscription, or
- add credits to wallet

### E. Customer Access
Must support:
- customer record creation
- purchase history
- access status
- portal magic link trigger

### F. Growth Layer
Must support:
- affiliate tracked links
- basic conversion mapping
- revenue attribution
- top-offer analytics

## User Flows
### Flow A - Workspace plan purchase
1. User signs up.
2. Creates workspace.
3. Chooses RevenueForge plan.
4. Pays through Mayar.
5. Webhook confirms payment.
6. Workspace is activated.

### Flow B - Offer creation and publication
1. Workspace owner opens AI Offer Studio.
2. Enters business data.
3. AI generates sales assets.
4. User edits and saves.
5. User maps monetization type.
6. Offer is published.

### Flow C - Buyer purchase
1. Buyer lands on offer page.
2. Clicks buy.
3. Completes Mayar checkout.
4. Redirects to success page.
5. Webhook confirms final payment state.
6. Access is provisioned.

### Flow D - Credit top-up
1. Buyer selects a credit pack.
2. Pays via Mayar.
3. Webhook confirms payment.
4. Credit wallet increases.

### Flow E - Affiliate attribution
1. Affiliate shares tracked link.
2. Visit is recorded.
3. Buyer purchases.
4. Conversion is attributed.

## Architecture Principle
Use RevenueForge as the **system of record for business logic** and Mayar as the **system of record for payment execution and customer portal access**.

## Key Dashboard Metrics
- gross revenue
- paid orders
- active subscriptions
- credits sold
- new customers
- affiliate-attributed revenue
- top offers
- failed or pending payments

## 7-Day Build Sequence
### Day 1
- project scaffold
- auth
- workspace setup
- base dashboard shell

### Day 2
- AI Offer Studio
- offer generation UI
- save/edit logic

### Day 3
- offer pages
- checkout initiation
- payment success/cancel pages

### Day 4
- webhook endpoint
- payment state handling
- workspace and access activation

### Day 5
- customers
- portal magic link
- subscriptions and credits

### Day 6
- affiliates
- analytics dashboard
- demo seed data

### Day 7
- UI polish
- edge cases
- reconciliation jobs
- demo prep

---

# Stage 3 - Architecture Lock, Schema, Routes, and Modules

## Goal
Lock the technical architecture before implementation.

## Stage 3 Deliverables
The agent must define and implement:
- folder structure
- migration plan
- route map
- model relationships
- env configuration
- module boundaries
- payment state machine
- page map
- seed plan

## Folder Structure
Use this architecture:

```text
app/
  Actions/
    AI/
    Billing/
    Customers/
    Credits/
    Affiliates/
    Webhooks/
  Data/
    DTOs/
    Enums/
  Events/
  Http/
    Controllers/
    Middleware/
    Requests/
  Jobs/
  Models/
  Policies/
  Services/
    AI/
    Mayar/
    Analytics/
    Affiliates/
    Billing/
  Support/
config/
  mayar.php
  revenueforge.php
resources/
  js/
    Pages/
    Components/
    Layouts/
routes/
  web.php
  api.php
  auth.php
database/
  migrations/
  seeders/
```

## Core Database Tables
### Workspace Layer
- users
- workspaces
- workspace_members
- workspace_settings
- workspace_plans

### Offer Layer
- offers
- offer_versions
- landing_pages
- ai_generations

### Customer Layer
- customers
- customer_access
- portal_sessions_log

### Commerce Layer
- orders
- payments
- payment_events
- subscriptions
- credit_wallets
- credit_ledgers

### Growth Layer
- affiliate_programs
- affiliate_links
- affiliate_clicks
- affiliate_conversions

### Metrics and Integration Layer
- analytics_events
- daily_metrics
- mayar_products
- mayar_customers
- mayar_webhook_logs

## Payment State Machine
### Order states
- draft
- pending
- paid
- failed
- cancelled
- expired
- refunded

### Payment states
- pending
- paid
- failed
- expired
- refunded

### Subscription states
- trialing
- active
- past_due
- cancelled
- expired

### Access states
- active
- inactive
- expired
- revoked

## Provisioning Rules
### If offer type is `one_time`
Create customer access with active product access.

### If offer type is `subscription`
Create or update subscription and membership access.

### If offer type is `credit_pack`
Create or update credit wallet and add ledger entry.

### If purchase is workspace plan
Activate workspace, store plan, and update plan status.

## Route Map
### Public Routes
- `/`
- `/pricing`
- `/o/{slug}`
- `/payment/success`
- `/payment/cancel`

### App Routes
- `/app`
- `/app/offers`
- `/app/offers/create`
- `/app/offers/{offer}/edit`
- `/app/customers`
- `/app/customers/{customer}`
- `/app/affiliates`
- `/app/analytics`
- `/app/billing`
- `/app/settings`

### API / Webhook Routes
- `/api/webhooks/mayar`
- authenticated API helpers if needed

## Env Keys
At minimum include:

```env
APP_NAME=RevenueForge
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=revenueforge
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAYAR_BASE_URL=
MAYAR_API_KEY=
MAYAR_WEBHOOK_SECRET=
MAYAR_PORTAL_SUBDOMAIN=
MAYAR_TIMEOUT=30

AI_PROVIDER=openai_compatible
AI_BASE_URL=
AI_API_KEY=
AI_MODEL=

REVENUEFORGE_DEFAULT_CURRENCY=IDR
REVENUEFORGE_TRIAL_DAYS=7
REVENUEFORGE_AFFILIATE_COOKIE_DAYS=30
REVENUEFORGE_ENABLE_CREDITS=true
REVENUEFORGE_ENABLE_AFFILIATES=true
REVENUEFORGE_ENABLE_PORTAL_MAGIC_LINK=true
```

## Seed Data Requirements
Create demo seeders for:
- 1 workspace owner
- 1 workspace
- 4 offers
- 12 customers
- sample orders and payments
- affiliate data
- analytics data
- daily metrics

## Demo Offers
Use these exact seeded offers:
- Template Bundle Pro
- Creator Club Monthly
- AI Credits 500
- RevenueForge Growth Plan

---

# Stage 4 - Concrete Implementation Blueprint

## Goal
Turn the locked architecture into an implementation order that is safe, coherent, and demo-friendly.

## Stage 4 Deliverables
The agent must define and implement:
- migration code batches
- model class skeletons
- controllers
- request classes
- service signatures
- action classes
- job pipeline
- webhook flow
- dashboard query layer
- UI page implementation order
- tests

## Implementation Order
### Batch 1 - Foundation
- config
- enums
- core migrations
- base models
- workspace resolver
- auth-protected app shell

### Batch 2 - Offers and AI
- offers CRUD
- AI generation service
- offer versioning
- offer editing pages

### Batch 3 - Billing
- Mayar client
- local order/payment creation
- checkout creation
- public checkout route
- success and cancel pages

### Batch 4 - Webhooks and Provisioning
- webhook receiver
- webhook log storage
- idempotent payment events
- workspace activation
- product access grant
- subscription activation
- credit top-up

### Batch 5 - Customers and Portal
- customer pages
- purchase history
- portal magic link action
- access records

### Batch 6 - Affiliates and Analytics
- tracked links
- click capture
- conversion attribution
- KPI cards
- chart queries
- daily metrics job

### Batch 7 - Reliability and Polish
- reconciliation jobs
- retry-safe processing
- error handling
- role checks
- seed data
- tests
- UI cleanup

## Controller Inventory
### Public Controllers
- PublicLandingController
- PricingController
- ShowPublicOfferController
- CreateOfferCheckoutController
- PaymentSuccessController
- PaymentCancelController

### App Controllers
- DashboardController
- AnalyticsController
- WorkspaceSettingsController

### Offer Controllers
- OfferIndexController
- OfferCreateController
- OfferStoreController
- OfferEditController
- OfferUpdateController
- OfferPublishController
- GenerateOfferCopyController

### Billing Controllers
- BillingSettingsController
- CreateWorkspaceCheckoutController
- SendPortalMagicLinkController

### Customer Controllers
- CustomerIndexController
- CustomerShowController
- SendCustomerPortalLinkController

### Affiliate Controllers
- AffiliateIndexController
- AffiliateLinkStoreController

### Webhook Controller
- ReceiveMayarWebhookController

## Request Classes
Create dedicated form requests for:
- StoreOfferRequest
- UpdateOfferRequest
- GenerateOfferCopyRequest
- CreateWorkspaceCheckoutRequest
- CreateOfferCheckoutRequest
- SendPortalMagicLinkRequest
- StoreAffiliateLinkRequest

## Service Layer
### `Services/AI`
- OfferGeneratorService
- PricingSuggestionService
- PromptBuilderService

### `Services/Mayar`
- MayarClient
- MayarPaymentService
- MayarCustomerService
- MayarMembershipService
- MayarCreditService
- MayarWebhookService
- MayarPortalService

### Other Services
- AffiliateAttributionService
- RevenueSummaryQuery
- TopOffersQuery
- RecentPaymentsQuery
- CustomerGrowthQuery

## Action Classes
### AI Actions
- GenerateOfferAction
- ImproveCopyAction
- SuggestPricingAction

### Billing Actions
- CreateWorkspaceCheckoutAction
- CreateOfferCheckoutAction
- ReconcilePaymentAction
- ActivateWorkspacePlanAction

### Customer Actions
- UpsertCustomerAction
- GrantOfferAccessAction
- SendPortalMagicLinkAction

### Credit Actions
- AddCreditsAction
- SpendCreditsAction
- SyncCreditBalanceAction

### Webhook Actions
- VerifyWebhookAction
- StoreWebhookAction
- ProcessWebhookEventAction

### Affiliate Actions
- CaptureAffiliateClickAction
- RecordAffiliateConversionAction

## Webhook Rules
The webhook system must be:
- queue-first
- idempotent
- duplicate-safe
- side-effect safe
- traceable through logs

### Mandatory Webhook Flow
1. Receive payload.
2. Store raw log.
3. Derive stable event key.
4. Reject duplicates.
5. Create payment event row.
6. Dispatch processing job.
7. Resolve payment and order.
8. Apply state change once.
9. Mark processed.
10. Trigger analytics updates.

### Critical Rule
Never unlock access from success redirect page alone.
Only unlock after webhook confirmation or explicit reconciliation.

## Access Provision Matrix
### One-time offer
Grant active product access.

### Subscription
Activate subscription and membership access.

### Credit pack
Create or update wallet and add credits.

### Workspace plan
Activate workspace plan and set workspace active.

## UI Page Build Order
1. Landing page
2. Dashboard page
3. Offer list
4. Offer create page
5. Offer edit page
6. Public offer page
7. Billing settings page
8. Customer index page
9. Customer detail page
10. Affiliate page
11. Analytics page
12. Payment success page
13. Payment cancel page

## Shared Vue Components
- MetricCard.vue
- DataTable.vue
- StatusBadge.vue
- OfferEditor.vue
- AiGenerationPanel.vue
- PortalLinkModal.vue
- CreditWalletCard.vue
- AffiliateLinkCard.vue
- WebhookLogDrawer.vue

## Test Priorities
### Feature Tests
- workspace owner can create offer
- AI generation stores version
- checkout creates order and payment
- duplicate webhook is ignored
- paid webhook grants one-time access
- paid webhook activates subscription
- paid webhook adds credits
- portal link action logs result
- affiliate click becomes conversion

### Unit Tests
- ProcessWebhookEventAction
- MayarPaymentService
- MayarCreditService
- AffiliateAttributionService

---

# Concrete Project Rules for the Agent

## Architecture Rules
- Controllers must stay thin.
- External API logic must stay inside `Services/Mayar`.
- Provisioning logic must happen through Actions.
- Status values should use enums or centralized constants.
- Queries for dashboard metrics should live in dedicated query/service classes.
- Use jobs for webhook and heavy post-payment work.
- Keep all payment mutations auditable.

## Reliability Rules
- Every checkout must create a local order and local payment first.
- Every external event must be logged.
- Every webhook side effect must be idempotent.
- Every payment status must be reconcilable.
- Success pages are visual only.
- Access must never be unlocked twice.

## UI Rules
- Premium SaaS appearance.
- Mobile-first responsive layout.
- High readability.
- Strong empty states.
- Good status badges and feedback states.
- Clean tables, cards, and forms.

## Product Rules
- Prioritize the golden path.
- Mayar must feel like the monetization backbone.
- AI must feel useful, not decorative.
- Analytics must reflect real business activity.
- The demo must feel smooth and believable.

---

# Final Build Priorities
Always prioritize in this order:

1. **Workspace setup**
2. **Offer creation**
3. **Mayar checkout integration**
4. **Webhook automation**
5. **Access or credit unlock**
6. **Customer portal trigger**
7. **Revenue analytics**
8. **Affiliate attribution**
9. **Polish and tests**

If time becomes tight, do not cut the golden path. Cut secondary enhancements instead.

---

# Expected Working Style
When executing this project, do the following:

1. Start from the current stage and state the scope.
2. List the exact files to create or update.
3. Implement in batches.
4. After each batch, provide a short checkpoint:
   - what was built
   - what works
   - what is next
5. If there are blockers, propose the safest implementation path instead of stalling.
6. Keep the build coherent with the product story.

---

# Final Instruction
Build **RevenueForge** as a polished, premium, competition-ready SaaS MVP.

The app must convincingly demonstrate that:
- AI helps creators and UMKM generate offers faster,
- Mayar powers monetization and payment execution,
- webhook events drive real automation,
- customers receive proper access,
- and the business owner can understand growth through a revenue dashboard.

Do not drift.
Do not overbuild unrelated modules.
Do not weaken the monetization story.
Protect the golden path and implement the product stage by stage.
