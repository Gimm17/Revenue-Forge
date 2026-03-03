<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    metrics: Object,
    recentPayments: Array,
    topOffers: Array,
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);
};

const metricCards = [
    {
        key: "gross_revenue",
        label: "Gross Revenue",
        format: "currency",
        icon: "revenue",
    },
    {
        key: "paid_orders",
        label: "Paid Orders",
        format: "number",
        icon: "orders",
    },
    {
        key: "active_subscriptions",
        label: "Active Subs",
        format: "number",
        icon: "subs",
    },
    {
        key: "credits_sold",
        label: "Credits Sold",
        format: "number",
        icon: "credits",
    },
    {
        key: "new_customers",
        label: "New Customers",
        format: "number",
        icon: "customers",
    },
];

const formatMetric = (card) => {
    const val = props.metrics?.[card.key] || 0;
    return card.format === "currency"
        ? formatCurrency(val)
        : val.toLocaleString();
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Dashboard</h1>
        </template>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div
                v-for="card in metricCards"
                :key="card.key"
                class="relative overflow-hidden rounded-xl bg-gradient-to-br from-white/[0.07] to-white/[0.03] border border-white/[0.08] p-5 group hover:border-cyan-500/30 transition-all duration-300"
            >
                <div
                    class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-violet-500/5 opacity-0 group-hover:opacity-100 transition-opacity"
                />
                <p
                    class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1"
                >
                    {{ card.label }}
                </p>
                <p class="text-2xl font-bold text-white">
                    {{ formatMetric(card) }}
                </p>

                <!-- Decorative glow -->
                <div
                    class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-cyan-500/10 blur-2xl"
                />
            </div>
        </div>

        <!-- Content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Offers -->
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-white/[0.06]">
                    <h2 class="text-sm font-semibold text-white">Top Offers</h2>
                </div>
                <div v-if="topOffers && topOffers.length > 0">
                    <div
                        v-for="(offer, i) in topOffers"
                        :key="offer.id"
                        class="flex items-center justify-between px-5 py-3 border-b border-white/[0.04] last:border-0 hover:bg-white/[0.02] transition-colors"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <span
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-white/[0.06] flex items-center justify-center text-xs font-medium text-gray-400"
                                >{{ i + 1 }}</span
                            >
                            <div class="min-w-0">
                                <p
                                    class="text-sm font-medium text-gray-200 truncate"
                                >
                                    {{ offer.title }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ offer.paid_orders_count }} orders
                                </p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-cyan-400">{{
                            formatCurrency(offer.total_revenue)
                        }}</span>
                    </div>
                </div>
                <div v-else class="px-5 py-12 text-center">
                    <svg
                        class="w-12 h-12 mx-auto text-gray-700 mb-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                        />
                    </svg>
                    <p class="text-sm text-gray-500">
                        No offers yet. Create your first offer to get started.
                    </p>
                </div>
            </div>

            <!-- Recent Payments -->
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-white/[0.06]">
                    <h2 class="text-sm font-semibold text-white">
                        Recent Payments
                    </h2>
                </div>
                <div v-if="recentPayments && recentPayments.length > 0">
                    <div
                        v-for="payment in recentPayments"
                        :key="payment.id"
                        class="flex items-center justify-between px-5 py-3 border-b border-white/[0.04] last:border-0 hover:bg-white/[0.02] transition-colors"
                    >
                        <div class="min-w-0">
                            <p
                                class="text-sm font-medium text-gray-200 truncate"
                            >
                                {{ payment.offer_title }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ payment.paid_at }}
                            </p>
                        </div>
                        <span class="text-sm font-semibold text-emerald-400"
                            >+{{ formatCurrency(payment.amount) }}</span
                        >
                    </div>
                </div>
                <div v-else class="px-5 py-12 text-center">
                    <svg
                        class="w-12 h-12 mx-auto text-gray-700 mb-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                        />
                    </svg>
                    <p class="text-sm text-gray-500">
                        No payments yet. Your recent transactions will appear
                        here.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
