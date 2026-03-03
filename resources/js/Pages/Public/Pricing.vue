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
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">
                        Simple, Transparent Pricing
                    </h1>
                    <p class="text-lg text-gray-400 max-w-2xl mx-auto">
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
                                : 'bg-white/[0.04] border-white/[0.08] hover:border-white/[0.15]',
                        ]"
                    >
                        <!-- Popular badge -->
                        <div
                            v-if="i === 1"
                            class="absolute -top-3 left-1/2 -translate-x-1/2"
                        >
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-cyan-500 to-violet-600 text-xs font-semibold text-white shadow-lg"
                            >
                                Most Popular
                            </span>
                        </div>

                        <h3 class="text-lg font-semibold text-white mb-1">
                            {{ plan.name }}
                        </h3>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-4xl font-bold text-white">{{
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
                                    class="w-5 h-5 text-cyan-400 mt-0.5 flex-shrink-0"
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
                                <span class="text-sm text-gray-300">{{
                                    feature
                                }}</span>
                            </li>
                        </ul>

                        <Link
                            href="/register"
                            :class="[
                                'w-full inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold transition-all',
                                i === 1
                                    ? 'text-white bg-gradient-to-r from-cyan-500 to-violet-600 hover:from-cyan-400 hover:to-violet-500 shadow-lg shadow-cyan-500/25'
                                    : 'text-white border border-white/[0.15] hover:bg-white/[0.06]',
                            ]"
                        >
                            Get Started
                        </Link>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else class="text-center py-16">
                    <p class="text-gray-400">Pricing plans coming soon.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
