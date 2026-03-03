<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({ metrics: Array, totals: Object, days: Number });

const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);

// Simple sparkline SVG
const createSparkline = (data, key) => {
    if (!data || data.length < 2) return "";
    const values = data.map((d) => d[key] || 0);
    const max = Math.max(...values, 1);
    const w = 200,
        h = 40;
    const points = values
        .map((v, i) => `${(i / (values.length - 1)) * w},${h - (v / max) * h}`)
        .join(" ");
    return `M ${points.replace(/ /g, " L ")}`;
};

const kpiCards = computed(() => [
    {
        label: "Gross Revenue",
        value: formatCurrency(props.totals?.gross_revenue),
        key: "gross_revenue",
        color: "#06b6d4",
    },
    {
        label: "Paid Orders",
        value: (props.totals?.paid_orders || 0).toLocaleString(),
        key: "paid_orders",
        color: "#8b5cf6",
    },
    {
        label: "New Customers",
        value: (props.totals?.new_customers || 0).toLocaleString(),
        key: "new_customers",
        color: "#10b981",
    },
    {
        label: "Credits Sold",
        value: (props.totals?.credits_sold || 0).toLocaleString(),
        key: "credits_sold",
        color: "#f59e0b",
    },
    {
        label: "Affiliate Revenue",
        value: formatCurrency(props.totals?.affiliate_revenue),
        key: "affiliate_revenue",
        color: "#ec4899",
    },
]);
</script>

<template>
    <Head title="Analytics" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Revenue Analytics</h1>
        </template>

        <template #actions>
            <div class="flex items-center gap-2">
                <Link
                    v-for="d in [7, 30, 90]"
                    :key="d"
                    :href="`/app/analytics?days=${d}`"
                    :class="[
                        'px-3 py-1.5 text-xs font-medium rounded-lg transition-colors',
                        days === d
                            ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30'
                            : 'text-gray-500 hover:text-gray-300 border border-transparent',
                    ]"
                    >{{ d }}d</Link
                >
            </div>
        </template>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div
                v-for="card in kpiCards"
                :key="card.key"
                class="relative overflow-hidden rounded-xl bg-white/[0.04] border border-white/[0.08] p-5"
            >
                <p
                    class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1"
                >
                    {{ card.label }}
                </p>
                <p class="text-2xl font-bold text-white">{{ card.value }}</p>
                <!-- Sparkline -->
                <div class="mt-3 h-10">
                    <svg
                        viewBox="0 0 200 40"
                        class="w-full h-full"
                        preserveAspectRatio="none"
                    >
                        <path
                            :d="createSparkline(metrics, card.key)"
                            fill="none"
                            :stroke="card.color"
                            stroke-width="2"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue chart area (simplified bar display) -->
        <div
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 mb-6"
        >
            <h2 class="text-sm font-semibold text-white mb-4">
                Daily Revenue (last {{ days }} days)
            </h2>
            <div
                v-if="metrics && metrics.length > 0"
                class="flex items-end gap-1 h-48"
            >
                <div
                    v-for="(m, i) in metrics"
                    :key="i"
                    class="flex-1 bg-gradient-to-t from-cyan-500/30 to-cyan-500/10 rounded-t hover:from-cyan-500/50 hover:to-cyan-500/20 transition-all group relative"
                    :style="{
                        height: `${Math.max((m.gross_revenue / Math.max(...metrics.map((d) => d.gross_revenue), 1)) * 100, 2)}%`,
                    }"
                    :title="`${m.date}: ${formatCurrency(m.gross_revenue)}`"
                >
                    <div
                        class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block bg-gray-900 text-white text-xs px-2 py-1 rounded whitespace-nowrap z-10"
                    >
                        {{ m.date }}: {{ formatCurrency(m.gross_revenue) }}
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-16 text-sm text-gray-500">
                No data yet for the selected period.
            </div>
        </div>

        <!-- Data table -->
        <div
            v-if="metrics && metrics.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
        >
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/[0.06]">
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            Date
                        </th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                        >
                            Revenue
                        </th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden sm:table-cell"
                        >
                            Orders
                        </th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden md:table-cell"
                        >
                            Customers
                        </th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden lg:table-cell"
                        >
                            Subs
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="m in [...metrics].reverse().slice(0, 15)"
                        :key="m.date"
                        class="border-b border-white/[0.04]"
                    >
                        <td class="px-4 py-2.5 text-sm text-gray-300">
                            {{ m.date }}
                        </td>
                        <td
                            class="px-4 py-2.5 text-sm text-cyan-400 text-right font-medium"
                        >
                            {{ formatCurrency(m.gross_revenue) }}
                        </td>
                        <td
                            class="px-4 py-2.5 text-sm text-gray-300 text-right hidden sm:table-cell"
                        >
                            {{ m.paid_orders }}
                        </td>
                        <td
                            class="px-4 py-2.5 text-sm text-gray-300 text-right hidden md:table-cell"
                        >
                            {{ m.new_customers }}
                        </td>
                        <td
                            class="px-4 py-2.5 text-sm text-gray-300 text-right hidden lg:table-cell"
                        >
                            {{ m.active_subscriptions }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
