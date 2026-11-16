<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    ArrowsPointingOutIcon,
    ArrowTrendingUpIcon,
    CurrencyDollarIcon,
    ShoppingCartIcon,
    UserGroupIcon,
    StarIcon,
    DocumentDuplicateIcon,
} from "@heroicons/vue/24/outline";

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
        icon: CurrencyDollarIcon,
        color: "text-[#00D261]",
        bgColor: "bg-[#00D261]/10",
        arrowIcon: ArrowTrendingUpIcon,
        trend: "+12.5%",
    },
    {
        key: "paid_orders",
        label: "Paid Orders",
        format: "number",
        icon: ShoppingCartIcon,
        color: "text-blue-500",
        bgColor: "bg-blue-500/10",
        arrowIcon: ArrowTrendingUpIcon,
        trend: "+8.2%",
    },
    {
        key: "active_subscriptions",
        label: "Active Subs",
        format: "number",
        icon: ArrowsPointingOutIcon,
        color: "text-purple-500",
        bgColor: "bg-purple-500/10",
        arrowIcon: ArrowTrendingUpIcon,
        trend: "+3.1%",
    },
    {
        key: "new_customers",
        label: "New Customers",
        format: "number",
        icon: UserGroupIcon,
        color: "text-orange-500",
        bgColor: "bg-orange-500/10",
        arrowIcon: ArrowTrendingUpIcon,
        trend: "+15.3%",
    },
    {
        key: "credits_sold",
        label: "Credits Sold",
        format: "number",
        icon: StarIcon,
        color: "text-amber-500",
        bgColor: "bg-amber-500/10",
        arrowIcon: ArrowTrendingUpIcon,
        trend: "+5.4%",
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

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl"
                    >
                        Dashboard Overview
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Welcome back! Here's what's happening with your revenue
                        today.
                    </p>
                </div>
                <!-- Action Button -->
                <div class="mt-4 flex sm:ml-4 sm:mt-0">
                    <button
                        type="button"
                        class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-[#25f47b] to-[#00D261] px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-[#25f47b]/30 transition-all hover:scale-105 hover:shadow-[#25f47b]/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#00D261]"
                    >
                        <svg
                            class="-ml-0.5 h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"
                            />
                        </svg>
                        New Campaign
                    </button>
                </div>
            </div>
        </template>

        <!-- KPI Cards -->
        <dl
            class="mx-auto grid grid-cols-1 gap-px bg-gray-900/5 sm:grid-cols-2 lg:grid-cols-5 rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-8 mt-2"
        >
            <div
                v-for="card in metricCards"
                :key="card.key"
                class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-6 sm:px-6 xl:px-8 group transition-all duration-300 hover:bg-gray-50/50"
            >
                <dt
                    class="text-sm font-medium leading-6 text-gray-500 flex items-center gap-2"
                >
                    <div :class="[card.bgColor, card.color, 'p-2 rounded-lg']">
                        <component
                            :is="card.icon"
                            class="h-5 w-5"
                            aria-hidden="true"
                        />
                    </div>
                </dt>
                <dd class="text-xs font-medium text-gray-700">
                    {{ card.label }}
                </dd>
                <dd
                    class="w-full flex-none text-3xl font-extrabold tracking-tight text-gray-900"
                >
                    {{ formatMetric(card) }}
                </dd>
            </div>
        </dl>

        <!-- Content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Offers Card -->
            <div
                class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden ring-1 ring-black/5 hover:shadow-md transition-shadow duration-300"
            >
                <div
                    class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50"
                >
                    <div class="flex items-center gap-3">
                        <div class="bg-[#00D261]/10 p-2 rounded-lg">
                            <DocumentDuplicateIcon
                                class="h-5 w-5 text-[#00D261]"
                            />
                        </div>
                        <h2 class="text-base font-bold text-gray-900">
                            Top Performing Offers
                        </h2>
                    </div>
                    <Link
                        :href="route('app.dashboard')"
                        class="text-sm font-semibold text-[#00D261] hover:text-[#009B48]"
                        >View all</Link
                    >
                </div>

                <div
                    v-if="topOffers && topOffers.length > 0"
                    class="divide-y divide-gray-100"
                >
                    <div
                        v-for="(offer, i) in topOffers"
                        :key="offer.id"
                        class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors group"
                    >
                        <div class="flex items-center gap-4 min-w-0">
                            <span
                                class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 group-hover:bg-[#00D261]/10 group-hover:text-[#00D261] transition-colors"
                                >{{ i + 1 }}</span
                            >
                            <div class="min-w-0">
                                <p
                                    class="text-sm font-bold text-gray-900 truncate"
                                >
                                    {{ offer.title }}
                                </p>
                                <p
                                    class="text-xs font-medium text-gray-500 mt-0.5"
                                >
                                    {{ offer.paid_orders_count }} successful
                                    orders
                                </p>
                            </div>
                        </div>
                        <span
                            class="text-sm font-extrabold text-[#00D261] bg-[#00D261]/10 px-3 py-1 rounded-full whitespace-nowrap"
                            >{{ formatCurrency(offer.total_revenue) }}</span
                        >
                    </div>
                </div>
                <div v-else class="px-6 py-16 text-center">
                    <div
                        class="mx-auto w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4"
                    >
                        <DocumentDuplicateIcon class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">
                        No offers yet
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Create your first offer to start generating revenue.
                    </p>
                    <div class="mt-6">
                        <button
                            type="button"
                            class="inline-flex items-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            <svg
                                class="-ml-0.5 mr-2 h-4 w-4 text-gray-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"
                                />
                            </svg>
                            Create Offer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Payments Card -->
            <div
                class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden ring-1 ring-black/5 hover:shadow-md transition-shadow duration-300"
            >
                <div
                    class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50"
                >
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-500/10 p-2 rounded-lg">
                            <CurrencyDollarIcon class="h-5 w-5 text-blue-500" />
                        </div>
                        <h2 class="text-base font-bold text-gray-900">
                            Recent Activity
                        </h2>
                    </div>
                </div>

                <div
                    v-if="recentPayments && recentPayments.length > 0"
                    class="divide-y divide-gray-100"
                >
                    <div
                        v-for="payment in recentPayments"
                        :key="payment.id"
                        class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors"
                    >
                        <div class="flex items-center gap-4 min-w-0">
                            <!-- Avatar Placeholder -->
                            <div
                                class="h-10 w-10 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center shrink-0 border border-gray-200"
                            >
                                <span class="text-gray-500 font-bold text-sm">{{
                                    payment.offer_title.charAt(0)
                                }}</span>
                            </div>
                            <div class="min-w-0">
                                <p
                                    class="text-sm font-bold text-gray-900 truncate"
                                >
                                    {{ payment.offer_title }}
                                </p>
                                <p
                                    class="text-xs font-medium text-gray-500 mt-0.5"
                                >
                                    Purchased • {{ payment.paid_at }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="text-sm font-extrabold text-blue-600 bg-blue-50 px-3 py-1 rounded-full whitespace-nowrap"
                            >+{{ formatCurrency(payment.amount) }}</span
                        >
                    </div>
                </div>
                <div v-else class="px-6 py-16 text-center">
                    <div
                        class="mx-auto w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4"
                    >
                        <CurrencyDollarIcon class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">
                        No recent payments
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Your latest transaction activity will appear here.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
