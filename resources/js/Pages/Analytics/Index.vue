<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import {
    ChartBarIcon,
    CurrencyDollarIcon,
    ShoppingBagIcon,
    UserPlusIcon,
    ArrowTrendingUpIcon,
    DevicePhoneMobileIcon,
    ComputerDesktopIcon,
    DeviceTabletIcon,
    QuestionMarkCircleIcon,
    GlobeAltIcon,
    FunnelIcon,
    CalendarDaysIcon,
    TrophyIcon,
    ListBulletIcon,
} from "@heroicons/vue/24/outline";

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
        color: "#0ea5e9", // Tailwind sky-500
        bgColor: "bg-sky-50 text-sky-600",
        icon: ChartBarIcon,
    },
    {
        label: "Gross Revenue",
        value: formatCurrency(props.totals?.gross_revenue),
        key: "gross_revenue",
        color: "#00D261", // Revenue Forge Green
        bgColor: "bg-[#00D261]/10 text-[#00D261]",
        icon: CurrencyDollarIcon,
    },
    {
        label: "Paid Orders",
        value: (props.totals?.paid_orders || 0).toLocaleString(),
        key: "paid_orders",
        color: "#8b5cf6", // Tailwind violet-500
        bgColor: "bg-violet-50 text-violet-600",
        icon: ShoppingBagIcon,
    },
    {
        label: "New Customers",
        value: (props.totals?.new_customers || 0).toLocaleString(),
        key: "new_customers",
        color: "#f59e0b", // Tailwind amber-500
        bgColor: "bg-amber-50 text-amber-600",
        icon: UserPlusIcon,
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
        color: "#f43f5e", // Tailwind rose-500
        bgColor: "bg-rose-50 text-rose-600",
        icon: ArrowTrendingUpIcon,
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

// Map for device icons
const getDeviceIcon = (type) => {
    const iconMap = {
        mobile: DevicePhoneMobileIcon,
        desktop: ComputerDesktopIcon,
        tablet: DeviceTabletIcon,
        unknown: QuestionMarkCircleIcon,
    };
    return iconMap[type] || QuestionMarkCircleIcon;
};

const deviceColors = {
    mobile: "#0ea5e9", // Sky
    desktop: "#8b5cf6", // Violet
    tablet: "#f59e0b", // Amber
    unknown: "#9ca3af", // Gray
};
</script>

<template>
    <Head title="Analytics" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl"
                    >
                        Analytics
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Track your revenue, visitors, and offer performance.
                    </p>
                </div>

                <div
                    class="flex items-center gap-2 bg-white rounded-xl shadow-sm ring-1 ring-inset ring-gray-300 p-1"
                >
                    <Link
                        v-for="d in [7, 30, 90]"
                        :key="d"
                        :href="route('app.analytics', { days: d })"
                        :class="[
                            'px-4 py-1.5 text-sm font-semibold rounded-lg transition-all',
                            days === d
                                ? 'bg-[#00D261]/10 text-[#009B48]'
                                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50',
                        ]"
                        >{{ d }}d</Link
                    >
                </div>
            </div>
        </template>

        <!-- KPI Cards -->
        <div
            class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 mt-4 mb-8"
        >
            <div
                v-for="card in kpiCards"
                :key="card.label"
                class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6 flex flex-col justify-between group hover:shadow-md transition-shadow h-full min-h-[140px]"
            >
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >{{ card.label }}</span
                        >
                        <div :class="['p-2 rounded-xl shrink-0', card.bgColor]">
                            <component :is="card.icon" class="w-5 h-5" />
                        </div>
                    </div>
                    <div
                        class="text-2xl xl:text-3xl font-extrabold text-gray-900 tracking-tight truncate"
                        :title="card.value"
                    >
                        {{ card.value }}
                    </div>
                </div>

                <div
                    class="mt-4 h-8 w-full opacity-60 transition-opacity group-hover:opacity-100"
                    v-if="card.key && metrics && metrics.length > 1"
                >
                    <svg
                        viewBox="0 0 100 100"
                        class="h-full w-full"
                        preserveAspectRatio="none"
                    >
                        <polyline
                            :points="createSparkline(metrics, card.key)"
                            fill="none"
                            :stroke="card.color"
                            stroke-width="4"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>
                <!-- Empty placeholder for cards without graphs -->
                <div v-else class="mt-4 h-8 w-full"></div>
            </div>
        </div>

        <!-- Funnel + Traffic Sources Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Conversion Funnel -->
            <div
                class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6"
            >
                <div class="flex items-center gap-2 mb-6 text-gray-900">
                    <FunnelIcon class="w-5 h-5 text-gray-400" />
                    <h2 class="text-base font-bold">Conversion Funnel</h2>
                </div>
                <div class="space-y-4">
                    <div
                        v-for="(step, i) in funnel"
                        :key="step.label"
                        class="space-y-1"
                    >
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-semibold text-gray-600">{{
                                step.label
                            }}</span>
                            <span
                                class="text-sm font-extrabold text-gray-900"
                                >{{ step.value.toLocaleString() }}</span
                            >
                        </div>
                        <div
                            class="h-8 bg-gray-100 rounded-xl overflow-hidden relative"
                        >
                            <div
                                class="h-full rounded-xl transition-all duration-700 flex items-center pl-3"
                                :style="{
                                    width:
                                        Math.max(
                                            (step.value / maxFunnel) * 100,
                                            2,
                                        ) + '%',
                                    backgroundColor: step.color + '20',
                                    borderLeft: '4px solid ' + step.color,
                                }"
                            >
                                <span
                                    v-if="i > 0 && funnel[i - 1].value > 0"
                                    class="text-xs font-bold"
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
            <div class="space-y-6">
                <!-- Traffic Sources -->
                <div
                    class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6"
                >
                    <div class="flex items-center gap-2 mb-6 text-gray-900">
                        <GlobeAltIcon class="w-5 h-5 text-gray-400" />
                        <h2 class="text-base font-bold">Traffic Sources</h2>
                    </div>
                    <div
                        v-if="trafficSources && trafficSources.length > 0"
                        class="space-y-4"
                    >
                        <div
                            v-for="src in trafficSources"
                            :key="src.source"
                            class="flex items-center gap-3"
                        >
                            <div class="flex-1 min-w-0">
                                <div
                                    class="flex items-center justify-between text-sm mb-1.5"
                                >
                                    <span
                                        class="text-gray-600 font-medium capitalize truncate"
                                        >{{ src.source }}</span
                                    >
                                    <span
                                        class="text-gray-900 font-bold ml-2"
                                        >{{ src.count }}</span
                                    >
                                </div>
                                <div class="h-2 bg-gray-100 rounded-full">
                                    <div
                                        class="h-full bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-full"
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
                    <div
                        v-else
                        class="text-sm font-medium text-gray-500 text-center py-6 bg-gray-50 rounded-xl"
                    >
                        No traffic data yet
                    </div>
                </div>

                <!-- Device Breakdown -->
                <div
                    class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6"
                >
                    <div class="flex items-center gap-2 mb-6 text-gray-900">
                        <DevicePhoneMobileIcon class="w-5 h-5 text-gray-400" />
                        <h2 class="text-base font-bold">Device Breakdown</h2>
                    </div>
                    <div
                        v-if="deviceTotal > 0"
                        class="flex items-center gap-6 divide-x divide-gray-100"
                    >
                        <div
                            v-for="(count, device) in deviceBreakdown"
                            :key="device"
                            class="flex-1 text-center pl-6 first:pl-0"
                        >
                            <div class="flex justify-center mb-2">
                                <component
                                    :is="
                                        device === 'mobile'
                                            ? DevicePhoneMobileIcon
                                            : device === 'desktop'
                                              ? ComputerDesktopIcon
                                              : device === 'tablet'
                                                ? DeviceTabletIcon
                                                : QuestionMarkCircleIcon
                                    "
                                    class="w-6 h-6 text-gray-400"
                                    :style="{ color: deviceColors[device] }"
                                />
                            </div>
                            <div
                                class="text-lg font-extrabold text-gray-900 mb-0.5"
                                :style="{ color: deviceColors[device] }"
                            >
                                {{ ((count / deviceTotal) * 100).toFixed(0) }}%
                            </div>
                            <div
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider"
                            >
                                {{ device }}
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="text-sm font-medium text-gray-500 text-center py-6 bg-gray-50 rounded-xl"
                    >
                        No device data yet
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div
            class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6 mb-8"
        >
            <div class="flex items-center gap-2 mb-6 text-gray-900">
                <CalendarDaysIcon class="w-5 h-5 text-gray-400" />
                <h2 class="text-base font-bold">Daily Revenue Timeline</h2>
            </div>
            <div
                v-if="metrics && metrics.length > 0"
                class="flex items-end gap-1 h-56 mt-8"
            >
                <div
                    v-for="m in metrics"
                    :key="m.date"
                    class="flex-1 group relative flex flex-col justify-end h-full"
                >
                    <!-- Value above bar on hover -->
                    <div
                        class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none"
                    >
                        <div
                            class="bg-gray-900 text-white text-xs font-bold px-2.5 py-1.5 rounded-lg shadow-xl whitespace-nowrap"
                        >
                            {{ formatCurrency(m.gross_revenue) }}
                            <div
                                class="text-[10px] text-gray-400 font-medium mt-0.5 font-normal"
                            >
                                {{ m.date }}
                            </div>
                            <div
                                class="absolute -bottom-1 left-1/2 -translate-x-1/2 border-solid border-t-gray-900 border-t-4 border-x-transparent border-x-4 border-b-0"
                            ></div>
                        </div>
                    </div>

                    <div
                        class="w-full bg-gradient-to-t from-[#00D261]/20 to-[#00D261]/40 rounded-t-sm transition-all group-hover:from-[#00D261]/40 group-hover:to-[#00D261]/60 cursor-pointer border-t-2 border-[#00D261]"
                        :style="{
                            height:
                                Math.max(
                                    (m.gross_revenue / maxRevenue) * 100,
                                    2,
                                ) + '%',
                        }"
                    />
                </div>
            </div>
            <div
                v-else
                class="text-center py-16 text-sm font-medium text-gray-500 bg-gray-50 rounded-xl"
            >
                No data yet for the selected period.
            </div>
        </div>

        <!-- Per-Offer Performance -->
        <div
            v-if="offerStats && offerStats.length > 0"
            class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden mb-8 ring-1 ring-black/5"
        >
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2 text-gray-900">
                    <TrophyIcon class="w-5 h-5 text-yellow-500" />
                    <h2 class="text-base font-bold">Top Performing Offers</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th
                                scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider sm:pl-6"
                            >
                                Offer
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Views
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Unique
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Orders
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Conv %
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Revenue
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr
                            v-for="o in offerStats"
                            :key="o.id"
                            class="hover:bg-gray-50/50 transition-colors"
                        >
                            <td
                                class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6"
                            >
                                <div
                                    class="text-sm font-bold text-gray-900 truncate max-w-[250px]"
                                >
                                    {{ o.title }}
                                </div>
                                <div
                                    class="text-[11px] font-medium text-gray-500 mt-0.5"
                                >
                                    /o/{{ o.slug }}
                                </div>
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-right text-sm font-medium text-gray-600"
                            >
                                {{ o.views.toLocaleString() }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-right text-sm font-medium text-gray-600"
                            >
                                {{ o.unique_views.toLocaleString() }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-gray-900"
                            >
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                                >
                                    {{ o.orders }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-right">
                                <span
                                    :class="[
                                        'text-xs font-bold px-2 py-1 rounded-md inline-flex',
                                        o.conversion_rate >= 5
                                            ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20'
                                            : o.conversion_rate >= 2
                                              ? 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20'
                                              : 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10',
                                    ]"
                                >
                                    {{ o.conversion_rate }}%
                                </span>
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-right text-sm font-extrabold text-[#00D261]"
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
            class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden ring-1 ring-black/5"
        >
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2 text-gray-900">
                    <ListBulletIcon class="w-5 h-5 text-gray-400" />
                    <h2 class="text-base font-bold">Daily Metrics Data</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th
                                scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider sm:pl-6"
                            >
                                Date
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Views
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Revenue
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Orders
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Customers
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Subs
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr
                            v-for="m in [...metrics].reverse().slice(0, 15)"
                            :key="m.date"
                            class="hover:bg-gray-50/50 transition-colors"
                        >
                            <td
                                class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-600 sm:pl-6"
                            >
                                {{ m.date }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-3 text-right text-sm font-medium text-gray-500"
                            >
                                {{ (m.page_views || 0).toLocaleString() }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-3 text-right text-sm font-extrabold text-[#00D261]"
                            >
                                {{ formatCurrency(m.gross_revenue) }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-3 text-right text-sm font-bold text-gray-900"
                            >
                                {{ m.paid_orders }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-3 text-right text-sm font-medium text-gray-500"
                            >
                                {{ m.new_customers }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-3 text-right text-sm font-medium text-gray-500"
                            >
                                {{ m.active_subscriptions }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
