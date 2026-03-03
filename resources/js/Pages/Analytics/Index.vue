<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    metrics: Array,
    totals: Object,
    days: Number,
    funnel: Array,
    offerStats: Array,
    trafficSources: Array,
    deviceBreakdown: Object,
});

const formatCurrency = (amount) => {
    if (!amount) return "Rp 0";
    return "Rp " + Number(amount).toLocaleString("id-ID");
};

/* Simple sparkline SVG */
const createSparkline = (data, key) => {
    if (!data || data.length < 2) return "";
    const values = data.map((d) => d[key] || 0);
    const max = Math.max(...values, 1);
    const points = values
        .map(
            (v, i) =>
                `${(i / (values.length - 1)) * 100},${100 - (v / max) * 80}`,
        )
        .join(" ");
    return points;
};

const kpiCards = computed(() => [
    {
        label: "Page Views",
        value: (props.totals?.page_views || 0).toLocaleString(),
        key: "page_views",
        color: "#06b6d4",
        icon: "👁️",
    },
    {
        label: "Gross Revenue",
        value: formatCurrency(props.totals?.gross_revenue),
        key: "gross_revenue",
        color: "#10b981",
        icon: "💰",
    },
    {
        label: "Paid Orders",
        value: (props.totals?.paid_orders || 0).toLocaleString(),
        key: "paid_orders",
        color: "#8b5cf6",
        icon: "📦",
    },
    {
        label: "New Customers",
        value: (props.totals?.new_customers || 0).toLocaleString(),
        key: "new_customers",
        color: "#f59e0b",
        icon: "👤",
    },
    {
        label: "Conversion Rate",
        value:
            props.totals?.page_views > 0
                ? (
                      (props.totals?.paid_orders / props.totals?.page_views) *
                      100
                  ).toFixed(1) + "%"
                : "0%",
        key: null,
        color: "#ec4899",
        icon: "🎯",
    },
]);

const maxFunnel = computed(() => {
    if (!props.funnel) return 1;
    return Math.max(...props.funnel.map((f) => f.value), 1);
});

const maxRevenue = computed(() => {
    if (!props.metrics || props.metrics.length === 0) return 1;
    return Math.max(...props.metrics.map((m) => m.gross_revenue || 0), 1);
});

const totalTraffic = computed(() => {
    if (!props.trafficSources) return 0;
    return props.trafficSources.reduce((sum, s) => sum + s.count, 0);
});

const deviceTotal = computed(() => {
    if (!props.deviceBreakdown) return 0;
    return Object.values(props.deviceBreakdown).reduce((sum, v) => sum + v, 0);
});

const deviceColors = {
    mobile: "#06b6d4",
    desktop: "#8b5cf6",
    tablet: "#f59e0b",
    unknown: "#6b7280",
};
const deviceIcons = {
    mobile: "📱",
    desktop: "💻",
    tablet: "📋",
    unknown: "❓",
};
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
                    :href="route('app.analytics', { days: d })"
                    :class="[
                        'px-3 py-1 text-xs font-medium rounded-full transition-all',
                        days === d
                            ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30'
                            : 'text-gray-500 hover:text-gray-300 border border-transparent',
                    ]"
                    >{{ d }}d</Link
                >
            </div>
        </template>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
            <div
                v-for="card in kpiCards"
                :key="card.label"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <div class="flex items-center justify-between mb-2">
                    <span
                        class="text-xs text-gray-500 uppercase tracking-wider"
                        >{{ card.label }}</span
                    >
                    <span class="text-lg">{{ card.icon }}</span>
                </div>
                <div class="text-lg font-bold text-white">{{ card.value }}</div>
                <svg
                    v-if="card.key && metrics && metrics.length > 1"
                    viewBox="0 0 100 100"
                    class="h-8 w-full mt-2"
                    preserveAspectRatio="none"
                >
                    <polyline
                        :points="createSparkline(metrics, card.key)"
                        fill="none"
                        :stroke="card.color"
                        stroke-width="2"
                    />
                </svg>
            </div>
        </div>

        <!-- Funnel + Traffic Sources Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <!-- Conversion Funnel -->
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6"
            >
                <h2 class="text-sm font-semibold text-white mb-4">
                    🔽 Conversion Funnel
                </h2>
                <div class="space-y-3">
                    <div
                        v-for="(step, i) in funnel"
                        :key="step.label"
                        class="space-y-1"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-300">{{
                                step.label
                            }}</span>
                            <span class="text-sm font-medium text-white">{{
                                step.value.toLocaleString()
                            }}</span>
                        </div>
                        <div
                            class="h-8 bg-white/[0.04] rounded-lg overflow-hidden relative"
                        >
                            <div
                                class="h-full rounded-lg transition-all duration-700 flex items-center pl-3"
                                :style="{
                                    width:
                                        Math.max(
                                            (step.value / maxFunnel) * 100,
                                            2,
                                        ) + '%',
                                    backgroundColor: step.color + '30',
                                    borderLeft: '3px solid ' + step.color,
                                }"
                            >
                                <span
                                    v-if="i > 0 && funnel[i - 1].value > 0"
                                    class="text-[10px] font-medium"
                                    :style="{ color: step.color }"
                                >
                                    {{
                                        (
                                            (step.value / funnel[i - 1].value) *
                                            100
                                        ).toFixed(1)
                                    }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Traffic Sources + Device -->
            <div class="space-y-4">
                <!-- Traffic Sources -->
                <div
                    class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6"
                >
                    <h2 class="text-sm font-semibold text-white mb-3">
                        🌐 Traffic Sources
                    </h2>
                    <div
                        v-if="trafficSources && trafficSources.length > 0"
                        class="space-y-2"
                    >
                        <div
                            v-for="src in trafficSources"
                            :key="src.source"
                            class="flex items-center gap-3"
                        >
                            <div class="flex-1 min-w-0">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span
                                        class="text-gray-300 capitalize truncate"
                                        >{{ src.source }}</span
                                    >
                                    <span class="text-white font-medium ml-2">{{
                                        src.count
                                    }}</span>
                                </div>
                                <div
                                    class="h-1.5 bg-white/[0.06] rounded-full mt-1"
                                >
                                    <div
                                        class="h-full bg-gradient-to-r from-cyan-500 to-violet-500 rounded-full"
                                        :style="{
                                            width:
                                                (src.count / totalTraffic) *
                                                    100 +
                                                '%',
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-sm text-gray-500 text-center py-4">
                        No traffic data yet
                    </div>
                </div>

                <!-- Device Breakdown -->
                <div
                    class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6"
                >
                    <h2 class="text-sm font-semibold text-white mb-3">
                        📱 Device Breakdown
                    </h2>
                    <div v-if="deviceTotal > 0" class="flex items-center gap-4">
                        <div
                            v-for="(count, device) in deviceBreakdown"
                            :key="device"
                            class="flex-1 text-center"
                        >
                            <div class="text-2xl mb-1">
                                {{ deviceIcons[device] || "❓" }}
                            </div>
                            <div class="text-sm font-medium text-white">
                                {{ ((count / deviceTotal) * 100).toFixed(0) }}%
                            </div>
                            <div class="text-[10px] text-gray-500 uppercase">
                                {{ device }}
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-sm text-gray-500 text-center py-4">
                        No device data yet
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 mb-6"
        >
            <h2 class="text-sm font-semibold text-white mb-4">
                📊 Daily Revenue (last {{ days }} days)
            </h2>
            <div
                v-if="metrics && metrics.length > 0"
                class="flex items-end gap-[2px] h-40"
            >
                <div
                    v-for="m in metrics"
                    :key="m.date"
                    class="flex-1 group relative"
                >
                    <div
                        class="bg-gradient-to-t from-cyan-500/60 to-violet-500/40 rounded-t transition-all hover:from-cyan-400/80 hover:to-violet-400/60 cursor-pointer"
                        :style="{
                            height:
                                Math.max(
                                    (m.gross_revenue / maxRevenue) * 100,
                                    2,
                                ) + '%',
                        }"
                    />
                    <div
                        class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:block z-10 bg-[#1a1c27] border border-white/[0.1] rounded-lg px-3 py-2 text-xs whitespace-nowrap shadow-xl"
                    >
                        <div class="text-white font-medium">{{ m.date }}</div>
                        <div class="text-cyan-400">
                            {{ formatCurrency(m.gross_revenue) }}
                        </div>
                        <div class="text-gray-400">
                            {{ m.paid_orders }} orders ·
                            {{ m.page_views || 0 }} views
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-16 text-sm text-gray-500">
                No data yet for the selected period.
            </div>
        </div>

        <!-- Per-Offer Performance -->
        <div
            v-if="offerStats && offerStats.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden mb-6"
        >
            <div class="px-6 py-4 border-b border-white/[0.06]">
                <h2 class="text-sm font-semibold text-white">
                    🏆 Per-Offer Performance
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.06]">
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                            >
                                Offer
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Views
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Unique
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Orders
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Conv %
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Revenue
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="o in offerStats"
                            :key="o.id"
                            class="border-b border-white/[0.04] hover:bg-white/[0.02] transition-colors"
                        >
                            <td class="px-4 py-3">
                                <div
                                    class="text-sm text-white font-medium truncate max-w-[200px]"
                                >
                                    {{ o.title }}
                                </div>
                                <div class="text-[10px] text-gray-600">
                                    /o/{{ o.slug }}
                                </div>
                            </td>
                            <td
                                class="px-4 py-3 text-right text-sm text-gray-300"
                            >
                                {{ o.views.toLocaleString() }}
                            </td>
                            <td
                                class="px-4 py-3 text-right text-sm text-gray-300"
                            >
                                {{ o.unique_views.toLocaleString() }}
                            </td>
                            <td
                                class="px-4 py-3 text-right text-sm text-white font-medium"
                            >
                                {{ o.orders }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span
                                    :class="[
                                        'text-xs font-semibold px-2 py-0.5 rounded-full',
                                        o.conversion_rate >= 5
                                            ? 'bg-emerald-500/20 text-emerald-400'
                                            : o.conversion_rate >= 2
                                              ? 'bg-amber-500/20 text-amber-400'
                                              : 'bg-gray-500/20 text-gray-400',
                                    ]"
                                >
                                    {{ o.conversion_rate }}%
                                </span>
                            </td>
                            <td
                                class="px-4 py-3 text-right text-sm text-cyan-400 font-medium"
                            >
                                {{ o.formatted_revenue }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daily Metrics Table -->
        <div
            v-if="metrics && metrics.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-white/[0.06]">
                <h2 class="text-sm font-semibold text-white">
                    📋 Daily Breakdown
                </h2>
            </div>
            <div class="overflow-x-auto">
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
                                Views
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Revenue
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Orders
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Customers
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
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
                                class="px-4 py-2.5 text-right text-sm text-gray-400"
                            >
                                {{ (m.page_views || 0).toLocaleString() }}
                            </td>
                            <td
                                class="px-4 py-2.5 text-right text-sm text-cyan-400 font-medium"
                            >
                                {{ formatCurrency(m.gross_revenue) }}
                            </td>
                            <td
                                class="px-4 py-2.5 text-right text-sm text-white"
                            >
                                {{ m.paid_orders }}
                            </td>
                            <td
                                class="px-4 py-2.5 text-right text-sm text-gray-400"
                            >
                                {{ m.new_customers }}
                            </td>
                            <td
                                class="px-4 py-2.5 text-right text-sm text-gray-400"
                            >
                                {{ m.active_subscriptions }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
