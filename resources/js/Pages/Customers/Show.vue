<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    customer: Object,
    orders: Array,
    access: Array,
    subscriptions: Array,
    creditBalance: Number,
});

const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);

const statusColor = (status) =>
    ({
        paid: "text-emerald-400",
        active: "text-emerald-400",
        pending: "text-amber-400",
        expired: "text-red-400",
        cancelled: "text-red-400",
        failed: "text-red-400",
    })[status] || "text-gray-400";

const form = useForm({});
const isSending = ref(false);

const sendPortalLink = () => {
    isSending.value = true;
    form.post(route("app.customers.portal-link", props.customer.id), {
        onFinish: () => (isSending.value = false),
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Customer: ${customer.name}`" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link
                    href="/app/customers"
                    class="text-gray-500 hover:text-gray-300"
                    ><svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        /></svg
                ></Link>
                <h1 class="text-xl font-bold text-white">
                    {{ customer.name }}
                </h1>

                <button
                    @click="sendPortalLink"
                    :disabled="isSending"
                    class="ml-auto inline-flex items-center gap-2 px-4 py-2 bg-white/[0.05] hover:bg-white/[0.1] text-white text-sm font-medium rounded-lg transition-colors border border-white/[0.1] disabled:opacity-50"
                >
                    <svg
                        v-if="isSending"
                        class="animate-spin w-4 h-4 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        ></path>
                    </svg>
                    {{ isSending ? "Sending..." : "Send Portal Link" }}
                </button>
            </div>
        </template>

        <!-- Info cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">Email</p>
                <p class="text-sm text-white">{{ customer.email }}</p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">
                    Credit Balance
                </p>
                <p class="text-lg font-bold text-cyan-400">
                    {{ creditBalance }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-4"
            >
                <p class="text-xs text-gray-500 uppercase mb-1">
                    Customer Since
                </p>
                <p class="text-sm text-white">{{ customer.created_at }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Orders -->
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-white/[0.06]">
                    <h2 class="text-sm font-semibold text-white">Orders</h2>
                </div>
                <div v-if="orders.length">
                    <div
                        v-for="o in orders"
                        :key="o.id"
                        class="flex items-center justify-between px-5 py-3 border-b border-white/[0.04] last:border-0"
                    >
                        <div>
                            <p class="text-sm text-gray-200">
                                {{ o.offer_title }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ o.paid_at || "Pending" }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">
                                {{ formatCurrency(o.amount) }}
                            </p>
                            <p
                                :class="statusColor(o.status)"
                                class="text-xs capitalize"
                            >
                                {{ o.status }}
                            </p>
                        </div>
                    </div>
                </div>
                <div v-else class="px-5 py-8 text-center text-sm text-gray-500">
                    No orders yet.
                </div>
            </div>

            <!-- Access & Subscriptions -->
            <div class="space-y-6">
                <div
                    class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
                >
                    <div class="px-5 py-4 border-b border-white/[0.06]">
                        <h2 class="text-sm font-semibold text-white">Access</h2>
                    </div>
                    <div v-if="access.length">
                        <div
                            v-for="a in access"
                            :key="a.id"
                            class="flex items-center justify-between px-5 py-3 border-b border-white/[0.04] last:border-0"
                        >
                            <p class="text-sm text-gray-200">
                                {{ a.offer_title }}
                            </p>
                            <p
                                :class="statusColor(a.status)"
                                class="text-xs capitalize"
                            >
                                {{ a.status }}
                            </p>
                        </div>
                    </div>
                    <div
                        v-else
                        class="px-5 py-8 text-center text-sm text-gray-500"
                    >
                        No access granted.
                    </div>
                </div>

                <div
                    class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
                >
                    <div class="px-5 py-4 border-b border-white/[0.06]">
                        <h2 class="text-sm font-semibold text-white">
                            Subscriptions
                        </h2>
                    </div>
                    <div v-if="subscriptions.length">
                        <div
                            v-for="s in subscriptions"
                            :key="s.id"
                            class="flex items-center justify-between px-5 py-3 border-b border-white/[0.04] last:border-0"
                        >
                            <div>
                                <p class="text-sm text-gray-200">
                                    {{ s.offer_title }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Renews: {{ s.current_period_end || "N/A" }}
                                </p>
                            </div>
                            <p
                                :class="statusColor(s.status)"
                                class="text-xs capitalize"
                            >
                                {{ s.status }}
                            </p>
                        </div>
                    </div>
                    <div
                        v-else
                        class="px-5 py-8 text-center text-sm text-gray-500"
                    >
                        No subscriptions.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
