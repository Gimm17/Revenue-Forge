<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    plans: Array,
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);
};

const intervalLabel = (interval) => {
    return { monthly: "/month", yearly: "/year", once: "" }[interval] || "";
};

// Tentukan URL tombol berdasarkan tipe plan
const getButtonHref = (plan) => {
    if (plan.is_free) {
        return "/register";
    }
    // Jika mayar_product_id berisi URL payment link Mayar, gunakan langsung
    if (plan.mayar_product_id && plan.mayar_product_id.startsWith("http")) {
        return plan.mayar_product_id;
    }
    // Fallback ke register jika belum dikonfigurasi
    return "/register";
};

// Apakah link menuju eksternal (Mayar)?
const isExternalLink = (plan) => {
    return (
        !plan.is_free &&
        plan.mayar_product_id &&
        plan.mayar_product_id.startsWith("http")
    );
};
</script>

<template>
    <Head title="Pricing" />

    <PublicLayout>
        <section class="relative py-24 sm:py-32">
            <!-- Background effects -->
            <div class="absolute inset-0">
                <div
                    class="absolute top-1/3 left-1/3 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl"
                />
                <div
                    class="absolute bottom-1/3 right-1/3 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl"
                />
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                        Simple, Transparent Pricing
                    </h1>
                    <p class="text-lg text-gray-500 max-w-2xl mx-auto">
                        Choose the plan that fits your business. Upgrade anytime
                        as you grow.
                    </p>
                </div>

                <div
                    v-if="plans && plans.length > 0"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto"
                >
                    <div
                        v-for="(plan, i) in plans"
                        :key="plan.id"
                        :class="[
                            'relative rounded-2xl border p-8 flex flex-col transition-all duration-300',
                            i === 1
                                ? 'bg-gradient-to-br from-cyan-500/10 to-violet-500/10 border-cyan-500/30 shadow-xl shadow-cyan-500/10 scale-105'
                                : 'bg-white/[0.04] border-gray-100 hover:border-white/[0.15]',
                        ]"
                    >
                        <!-- Popular badge -->
                        <div
                            v-if="i === 1"
                            class="absolute -top-3 left-1/2 -translate-x-1/2"
                        >
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-[#25f47b] to-[#00D261] text-xs font-semibold text-gray-900 shadow-lg"
                            >
                                Most Popular
                            </span>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            {{ plan.name }}
                        </h3>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-5xl font-extrabold tracking-tight text-gray-900 sm:text-6xl">{{
                                plan.formatted_price
                            }}</span>
                            <span
                                v-if="intervalLabel(plan.interval)"
                                class="text-sm text-gray-500"
                                >{{ intervalLabel(plan.interval) }}</span
                            >
                        </div>

                        <!-- Features -->
                        <ul v-if="plan.features" class="space-y-3 mb-8 flex-1">
                            <li
                                v-for="feature in plan.features"
                                :key="feature"
                                class="flex items-start gap-3"
                            >
                                <svg
                                    class="w-5 h-5 text-[#00D261] mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span class="text-sm text-gray-900">{{
                                    feature
                                }}</span>
                            </li>
                        </ul>

                        <!-- ✅ Tombol Get Started yang sudah diperbaiki -->
                        <!-- Plan BERBAYAR dengan Mayar link → buka halaman bayar Mayar di tab baru -->
                        <a
                            v-if="isExternalLink(plan)"
                            :href="getButtonHref(plan)"
                            target="_blank"
                            rel="noopener noreferrer"
                            :class="[
                                'w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold transition-all',
                                i === 1
                                    ? 'text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] hover:scale-105 shadow-lg shadow-[#25f47b]/30'
                                    : 'text-gray-900 border border-gray-200 hover:bg-gray-50',
                            ]"
                        >
                            Upgrade Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>

                        <!-- Plan GRATIS atau Mayar link belum diisi → arahkan ke /register -->
                        <Link
                            v-else
                            :href="getButtonHref(plan)"
                            :class="[
                                'w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold transition-all',
                                i === 1
                                    ? 'text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] hover:scale-105 shadow-lg shadow-[#25f47b]/30'
                                    : 'text-gray-900 border border-gray-200 hover:bg-gray-50',
                            ]"
                        >
                            {{ plan.is_free ? 'Get Started Free' : 'Get Started' }}
                        </Link>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else class="text-center py-16">
                    <p class="text-gray-500">Pricing plans coming soon.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
