<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({ customers: Object, filters: Object });

// Simple vanilla debounce to avoid needing lodash if it isn't loaded
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

const search = ref(props.filters?.search || "");

watch(
    search,
    debounce((newSearch) => {
        router.get(
            route("app.customers.index"),
            { search: newSearch },
            { preserveState: true, replace: true, preserveScroll: true },
        );
    }, 300),
);

const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);
</script>

<template>
    <Head title="Customers" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Customers</h1>
        </template>

        <!-- Filters -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search customers by name, email, or phone..."
                class="w-full max-w-md px-4 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors placeholder-gray-500"
            />
        </div>

        <div
            v-if="customers.data && customers.data.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
        >
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/[0.06]">
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Name
                        </th>
                        <th
                            class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell"
                        >
                            Email
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Orders
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell"
                        >
                            Total Spent
                        </th>
                        <th
                            class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell"
                        >
                            Joined
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="customer in customers.data"
                        :key="customer.id"
                        class="border-b border-white/[0.04] hover:bg-white/[0.02] transition-colors"
                    >
                        <td class="px-5 py-3">
                            <Link
                                :href="route('app.customers.show', customer.id)"
                                class="text-sm font-medium text-white hover:text-cyan-400 transition-colors"
                                >{{ customer.name }}</Link
                            >
                        </td>
                        <td
                            class="px-5 py-3 text-sm text-gray-400 hidden sm:table-cell"
                        >
                            {{ customer.email }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-300 text-right">
                            {{ customer.paid_orders_count }}
                        </td>
                        <td
                            class="px-5 py-3 text-sm text-cyan-400 text-right font-medium hidden md:table-cell"
                        >
                            {{ formatCurrency(customer.total_spent) }}
                        </td>
                        <td
                            class="px-5 py-3 text-sm text-gray-500 text-right hidden lg:table-cell"
                        >
                            {{ customer.created_at }}
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
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>
            <h3 class="text-lg font-semibold text-white mb-2">
                No customers yet
            </h3>
            <p class="text-sm text-gray-500">
                Customers will appear here after their first purchase.
            </p>
        </div>
    </AppLayout>
</template>
