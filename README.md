<div align="center">

# 💰 RevenueForge

### Creator & SaaS Offer, Checkout, Affiliate & Revenue Analytics Platform

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![Vue](https://img.shields.io/badge/Vue-3%20%2B%20Inertia.js-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org/)
[![Payments](https://img.shields.io/badge/Checkout-Midtrans-002855?style=for-the-badge)](https://midtrans.com/)
[![Realtime](https://img.shields.io/badge/WebSockets-Laravel%20Reverb-FF2D20?style=for-the-badge)](https://laravel.com/docs/reverb)

*Multi-workspace SaaS platform for building high-converting offer landing pages, processing Midtrans payments, managing affiliates & coupons, and tracking cash flow and conversion analytics.*

</div>

---

## ✨ Key Features

- **Drag-and-Drop Offer & Landing Page Builder** — Create, duplicate, publish, and customize offer pages with social proof testimonials and pricing tiers (`/o/{slug}`).
- **Midtrans Checkout & Webhook Pipeline** — Production payment gateway integration (`MidtransCheckoutController`, `MidtransWebhookController`), plus a built-in sandbox demo checkout & webhook log viewer.
- **CRM, Coupons & Affiliate System** — Customer portal links, CSV export, coupon management, affiliate link tracking, and email broadcasts.
- **Real-Time Revenue Analytics** — ApexCharts dashboards, conversion metrics, billing controls, and workspace notifications backed by Pinia & Laravel Reverb.

---

## 🚀 Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```
