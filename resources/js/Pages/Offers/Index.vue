<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
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

    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Offers</h1>
        </template>

        <template #actions>
            <Link
                :href="route('app.offers.create')"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
            >
                <svg
                    class="w-4 h-4"
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
                class="flex-1 w-full max-w-md px-4 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors placeholder-gray-500"
            />
            <select
                v-model="typeFilter"
                class="w-full sm:w-48 px-4 py-2 bg-[#13151d] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors"
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
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
        >
            <Link
                v-for="offer in offers.data"
                :key="offer.id"
                :href="route('app.offers.edit', offer.id)"
                class="group rounded-xl bg-white/[0.04] border border-white/[0.08] p-5 hover:border-cyan-500/30 transition-all duration-300 block"
            >
                <div class="flex items-start justify-between mb-3">
                    <span
                        :class="[
                            typeColorMap[offer.type_color] || typeColorMap.blue,
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border',
                        ]"
                    >
                        {{ offer.type_label }}
                    </span>

                    <div class="flex items-center gap-2">
                        <button
                            v-if="offer.is_published"
                            @click.prevent="copyLink(offer.slug)"
                            class="opacity-0 group-hover:opacity-100 p-1.5 rounded-md hover:bg-white/[0.08] text-gray-400 hover:text-cyan-400 transition-all"
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
                            class="opacity-0 group-hover:opacity-100 p-1.5 rounded-md hover:bg-white/[0.08] text-gray-400 hover:text-cyan-400 transition-all"
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
                        <span
                            :class="[
                                'w-2 h-2 rounded-full mt-1',
                                offer.is_published
                                    ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]'
                                    : 'bg-gray-600',
                            ]"
                            :title="offer.is_published ? 'Published' : 'Draft'"
                        />
                    </div>
                </div>
                <h3
                    class="text-base font-semibold text-white mb-1 group-hover:text-cyan-400 transition-colors"
                >
                    {{ offer.title }}
                </h3>
                <p class="text-sm text-gray-500 mb-3">
                    {{ offer.formatted_price }}
                </p>
                <div
                    class="flex items-center justify-between text-xs text-gray-500"
                >
                    <span>{{ offer.paid_orders_count }} orders</span>
                    <span>{{ offer.created_at }}</span>
                </div>
            </Link>
        </div>

        <!-- Empty state -->
        <div v-else class="text-center py-20">
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-cyan-500/10 to-violet-500/10 flex items-center justify-center"
            >
                <svg
                    class="w-8 h-8 text-gray-600"
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
            <h3 class="text-lg font-semibold text-white mb-2">No offers yet</h3>
            <p class="text-sm text-gray-500 mb-6">
                Create your first offer with AI assistance and start monetizing.
            </p>
            <Link
                :href="route('app.offers.create')"
                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all"
            >
                <svg
                    class="w-4 h-4"
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
    </AppLayout>
</template>
