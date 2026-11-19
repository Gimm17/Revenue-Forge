# RevenueForge
> Multi-workspace SaaS platform for high-converting offer pages, Midtrans checkout pipelines, affiliate tracking, and real-time revenue analytics.

![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Vue 3](https://img.shields.io/badge/Vue-3_+_Inertia.js-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white)
![Midtrans](https://img.shields.io/badge/Checkout-Midtrans-002855?style=flat-square)
![Reverb](https://img.shields.io/badge/Realtime-Laravel_Reverb-F97316?style=flat-square)

### Core Modules

| Module | Endpoints / Components | Highlights |
| :--- | :--- | :--- |
| **Offer Page Builder** | `/o/{slug}` | Drag-and-drop pricing tiers, testimonials & page duplication |
| **Midtrans Pipeline** | `MidtransCheckoutController`, `MidtransWebhookController` | Production gateway + built-in sandbox demo & webhook logs |
| **Affiliate & CRM** | Coupons, Referral Links, Email Broadcasts | Customer portal links & CSV export |
| **Analytics** | ApexCharts + Pinia + Reverb | Conversion funnels, cash flow & live workspace notifications |

### Local Quickstart

```bash
composer install && npm install && cp .env.example .env
php artisan key:generate && php artisan migrate --seed
php artisan serve & npm run dev
```
