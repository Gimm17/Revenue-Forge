<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({ orders: Object, stats: Object });

const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);

const statusColor = (status) =>
    ({
        paid: "bg-emerald-500/10 text-emerald-400 border-emerald-500/20",
        pending: "bg-amber-500/10 text-amber-400 border-amber-500/20",
        failed: "bg-red-500/10 text-red-400 border-red-500/20",
        draft: "bg-gray-500/10 text-gray-400 border-gray-500/20",
    })[status] || "bg-gray-500/10 text-gray-400 border-gray-500/20";
</script>

<template>
    <Head title="Billing" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Billing & Orders</h1>
        </template>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">
                    Total Revenue
                </p>
                <p class="text-xl font-bold text-cyan-400">
                    {{ formatCurrency(stats.total_revenue) }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">Total Orders</p>
                <p class="text-xl font-bold text-white">
                    {{ stats.total_orders }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">Paid</p>
                <p class="text-xl font-bold text-emerald-400">
                    {{ stats.paid_orders }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">Pending</p>
                <p class="text-xl font-bold text-amber-400">
                    {{ stats.pending_orders }}
                </p>
            </div>
        </div>

        <!-- Orders Table -->
        <div
            v-if="orders.data && orders.data.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
        >
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/[0.06]">
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            Offer
                        </th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell"
                        >
                            Customer
                        </th>
                        <th
                            class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase"
                        >
                            Status
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                        >
                            Amount
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden md:table-cell"
                        >
                            Method
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden lg:table-cell"
                        >
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in orders.data"
                        :key="order.id"
                        class="border-b border-white/[0.04] hover:bg-white/[0.02]"
                    >
                        <td class="px-5 py-3 text-sm text-white font-medium">
                            {{ order.offer_title }}
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <p class="text-sm text-gray-300">
                                {{ order.customer_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ order.customer_email }}
                            </p>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span
                                :class="[
                                    statusColor(order.status),
                                    'inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border',
                                ]"
                            >
                                {{ order.status_label }}
                            </span>
                        </td>
                        <td
                            class="px-5 py-3 text-sm text-right font-medium"
                            :class="
                                order.status === 'paid'
                                    ? 'text-cyan-400'
                                    : 'text-gray-400'
                            "
                        >
                            {{ order.formatted_amount }}
                        </td>
                        <td
                            class="px-5 py-3 text-sm text-gray-500 text-right hidden md:table-cell"
                        >
                            {{ order.payment_method }}
                        </td>
                        <td
                            class="px-5 py-3 text-sm text-gray-500 text-right hidden lg:table-cell"
                        >
                            {{ order.created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="text-center py-20">
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
            <h3 class="text-lg font-semibold text-white mb-2">No orders yet</h3>
            <p class="text-sm text-gray-500">
                Orders will appear here after your first sale.
            </p>
        </div>
    </AppLayout>
</template>
