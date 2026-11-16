<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    offers: Object,
    offerTypes: Array,
    filters: Object,
});

// Simple vanilla debounce to avoid needing lodash if it isn't loaded
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

const search = ref(props.filters?.search || "");
const typeFilter = ref(props.filters?.type || "");

watch(
    [search, typeFilter],
    debounce(([newSearch, newType]) => {
        router.get(
            route("app.offers.index"),
            { search: newSearch, type: newType },
            { preserveState: true, replace: true, preserveScroll: true },
        );
    }, 300),
);

const typeColorMap = {
    blue: "bg-blue-500/10 text-blue-400 border-blue-500/20",
    violet: "bg-violet-500/10 text-violet-400 border-violet-500/20",
    amber: "bg-amber-500/10 text-amber-400 border-amber-500/20",
    cyan: "bg-cyan-500/10 text-cyan-400 border-cyan-500/20",
};

const duplicateOffer = (id) => {
    if (confirm("Duplicate this offer?")) {
        router.post(route("app.offers.duplicate", id));
    }
};

const copyLink = (slug) => {
    const url = route("offer.show", { slug });
    navigator.clipboard.writeText(url);
    // Could add a toast here, but simple alert or visual feedback is fine
    alert("Link copied: " + url);
};

const viewPage = (slug) => {
    window.open(route("offer.show", { slug }), "_blank");
};
</script>

<template>
    <Head title="Offers" />

    <AuthenticatedLayout>
        <template #header>
            <h1
                class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl"
            >
                Offers
            </h1>
        </template>

        <template #actions>
            <Link
                :href="route('app.offers.create')"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#25f47b] to-[#00D261] px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-[#25f47b]/30 transition-all hover:scale-105 hover:shadow-[#25f47b]/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#00D261]"
            >
                <svg
                    class="-ml-0.5 h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>
                New Offer
            </Link>
        </template>

        <!-- Filters -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search offers by title or slug..."
                class="flex-1 w-full max-w-md px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-900 text-sm focus:border-[#00D261]/50 focus:outline-none focus:ring-1 focus:ring-[#00D261]/30 transition-colors placeholder-gray-400 shadow-sm"
            />
            <select
                v-model="typeFilter"
                class="w-full sm:w-48 px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-900 text-sm focus:border-[#00D261]/50 focus:outline-none focus:ring-1 focus:ring-[#00D261]/30 transition-colors shadow-sm"
            >
                <option value="">All Types</option>
                <option v-for="t in offerTypes" :key="t.value" :value="t.value">
                    {{ t.label }}
                </option>
            </select>
        </div>

        <!-- Offers grid -->
        <div
            v-if="offers.data && offers.data.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <Link
                v-for="offer in offers.data"
                :key="offer.id"
                :href="route('app.offers.edit', offer.id)"
                class="group rounded-2xl bg-white shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 block relative overflow-hidden ring-1 ring-black/5"
            >
                <div class="flex items-start justify-between mb-4">
                    <span
                        :class="[
                            typeColorMap[offer.type_color] || typeColorMap.blue,
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border',
                        ]"
                    >
                        {{ offer.type_label }}
                    </span>

                    <div class="flex items-center gap-1">
                        <button
                            v-if="offer.is_published"
                            @click.prevent="copyLink(offer.slug)"
                            class="opacity-0 group-hover:opacity-100 p-2 rounded-lg hover:bg-gray-50 text-gray-400 hover:text-[#00D261] transition-all"
                            title="Copy public link"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                        </button>
                        <button
                            v-if="offer.is_published"
                            @click.prevent="viewPage(offer.slug)"
                            class="opacity-0 group-hover:opacity-100 p-2 rounded-lg hover:bg-gray-50 text-gray-400 hover:text-[#00D261] transition-all"
                            title="View public page"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                />
                            </svg>
                        </button>
                        <button
                            @click.prevent="duplicateOffer(offer.id)"
                            class="opacity-0 group-hover:opacity-100 p-2 rounded-lg hover:bg-gray-50 text-gray-400 hover:text-blue-500 transition-all"
                            title="Duplicate offer"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                />
                            </svg>
                        </button>
                        <span
                            :class="[
                                'w-2 h-2 rounded-full mt-1 ml-1',
                                offer.is_published
                                    ? 'bg-[#00D261] shadow-[0_0_8px_rgba(0,210,97,0.5)]'
                                    : 'bg-gray-300',
                            ]"
                            :title="offer.is_published ? 'Published' : 'Draft'"
                        />
                    </div>
                </div>
                <h3
                    class="text-lg font-bold text-gray-900 mb-2 truncate group-hover:text-[#00D261] transition-colors"
                >
                    {{ offer.title }}
                </h3>
                <p class="text-sm font-semibold text-gray-500 mb-4">
                    {{ offer.formatted_price }}
                </p>
                <div
                    class="flex items-center justify-between text-xs font-medium text-gray-400 pt-4 border-t border-gray-50"
                >
                    <span>{{ offer.paid_orders_count }} successful orders</span>
                    <span>Created {{ offer.created_at }}</span>
                </div>
            </Link>
        </div>

        <!-- Empty state -->
        <div
            v-else
            class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100 ring-1 ring-black/5"
        >
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-[#00D261]/10 flex items-center justify-center"
            >
                <svg
                    class="w-8 h-8 text-[#00D261]"
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
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No offers yet</h3>
            <p class="text-sm text-gray-500 mb-6">
                Create your first offer to start generating revenue.
            </p>
            <Link
                :href="route('app.offers.create')"
                class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 transition-all hover:bg-gray-50"
            >
                <svg
                    class="-ml-0.5 h-5 w-5 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>
                Create Your First Offer
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
