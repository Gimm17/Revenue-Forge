<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { ArrowDownTrayIcon, UserGroupIcon } from "@heroicons/vue/24/outline";

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
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl"
                    >
                        Customers
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        View and manage the people who have purchased from you.
                    </p>
                </div>
                <!-- Action Button -->
                <div class="mt-4 flex sm:ml-4 sm:mt-0">
                    <a
                        :href="route('app.customers.export')"
                        class="inline-flex items-center gap-x-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                    >
                        <ArrowDownTrayIcon
                            class="-ml-0.5 h-5 w-5 text-gray-500"
                            aria-hidden="true"
                        />
                        Export CSV
                    </a>
                </div>
            </div>
        </template>

        <!-- Filters -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4 mt-4">
            <div class="relative flex-1 max-w-md">
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                >
                    <svg
                        class="h-5 w-5 text-gray-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search customers by name, email, or phone..."
                    class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-[#00D261] sm:text-sm sm:leading-6"
                />
            </div>
        </div>

        <div
            v-if="customers.data && customers.data.length > 0"
            class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden ring-1 ring-black/5"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th
                                scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider sm:pl-6"
                            >
                                Name
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell"
                            >
                                Email
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Orders
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell"
                            >
                                Total Spent
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell"
                            >
                                Joined
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr
                            v-for="customer in customers.data"
                            :key="customer.id"
                            class="hover:bg-gray-50/50 transition-colors"
                        >
                            <td
                                class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6"
                            >
                                <div class="flex items-center">
                                    <div class="h-10 w-10 shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center border border-gray-200"
                                        >
                                            <span
                                                class="text-gray-500 font-bold text-sm"
                                                >{{
                                                    customer.name.charAt(0)
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <Link
                                            :href="
                                                route(
                                                    'app.customers.show',
                                                    customer.id,
                                                )
                                            "
                                            class="text-sm font-bold text-gray-900 hover:text-[#00D261] transition-colors"
                                            >{{ customer.name }}</Link
                                        >
                                    </div>
                                </div>
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-medium hidden sm:table-cell"
                            >
                                {{ customer.email }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-semibold text-right"
                            >
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                                >
                                    {{ customer.paid_orders_count }}
                                </span>
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-sm font-extrabold text-[#00D261] text-right hidden md:table-cell"
                            >
                                {{ formatCurrency(customer.total_spent) }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-medium text-right hidden lg:table-cell"
                            >
                                {{ customer.created_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            v-else
            class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm mt-6"
        >
            <div
                class="mx-auto w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6"
            >
                <UserGroupIcon class="w-10 h-10 text-gray-500" />
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 mb-2">
                No customers yet
            </h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                When people purchase your offers, they will automatically be
                added to your customer list here.
            </p>
        </div>
    </AuthenticatedLayout>
</template>
