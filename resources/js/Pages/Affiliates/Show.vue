<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ program: Object, offers: Array });

const showCreateLink = ref(false);
const form = useForm({
    name: "",
    email: "",
    offer_id: "",
});

const submit = () => {
    form.post(route("app.affiliates.links.store", props.program.id), {
        onSuccess: () => {
            showCreateLink.value = false;
            form.reset();
        },
    });
};

const copyLink = (url) => {
    navigator.clipboard.writeText(url);
    alert("Link copied to clipboard: " + url);
};

const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);
</script>

<template>
    <Head :title="`${program.name} - Affiliates`" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('app.affiliates.index')"
                    class="text-gray-500 hover:text-gray-300 transition-colors"
                >
                    <svg
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
                        />
                    </svg>
                </Link>
                <h1 class="text-xl font-bold text-white">{{ program.name }}</h1>
                <span
                    :class="[
                        program.is_active
                            ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                            : 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                        'text-xs px-2 py-0.5 rounded-full border hidden sm:inline-flex',
                    ]"
                >
                    {{ program.is_active ? "Active" : "Inactive" }}
                </span>
            </div>
        </template>

        <template #actions>
            <button
                @click="showCreateLink = !showCreateLink"
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
                Generate Link
            </button>
        </template>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-5"
            >
                <p
                    class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider"
                >
                    Commission
                </p>
                <p class="text-2xl font-bold text-white">
                    {{ program.commission_value
                    }}{{
                        program.commission_type === "percentage" ? "%" : " IDR"
                    }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-5"
            >
                <p
                    class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider"
                >
                    Total Links
                </p>
                <p class="text-2xl font-bold text-white">
                    {{ program.links_count }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-5"
            >
                <p
                    class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider"
                >
                    Total Clicks
                </p>
                <p class="text-2xl font-bold text-white">
                    {{
                        program.links.reduce(
                            (sum, link) => sum + link.clicks_count,
                            0,
                        )
                    }}
                </p>
            </div>
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-5"
            >
                <p
                    class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider"
                >
                    Conversions
                </p>
                <p class="text-2xl font-bold text-emerald-400">
                    {{
                        program.links.reduce(
                            (sum, link) => sum + link.conversions_count,
                            0,
                        )
                    }}
                </p>
            </div>
        </div>

        <!-- Generate Link Form -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="showCreateLink"
                class="mb-6 rounded-xl bg-gradient-to-br from-cyan-500/[0.06] to-violet-500/[0.06] border border-cyan-500/20 p-6"
            >
                <h2 class="text-sm font-semibold text-white mb-4">
                    Generate Affiliate Link
                </h2>
                <form
                    @submit.prevent="submit"
                    class="grid grid-cols-1 sm:grid-cols-3 gap-4"
                >
                    <div>
                        <label class="block text-xs text-gray-400 mb-1"
                            >Affiliate Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            placeholder="John Doe"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1"
                            >Affiliate Email</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            placeholder="john@example.com"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1"
                            >Target Offer (Optional)</label
                        >
                        <select
                            v-model="form.offer_id"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        >
                            <option value="">All Offers (Storefront)</option>
                            <option
                                v-for="offer in offers"
                                :key="offer.id"
                                :value="offer.id"
                            >
                                {{ offer.title }}
                            </option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? "Generating..."
                                    : "Generate Link"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </transition>

        <!-- Links Table -->
        <div
            class="rounded-xl bg-white/[0.02] border border-white/[0.05] overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-white/[0.02] border-b border-white/[0.05]"
                        >
                            <th
                                class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                            >
                                Affiliate
                            </th>
                            <th
                                class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                            >
                                Target
                            </th>
                            <th
                                class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                            >
                                Link Code
                            </th>
                            <th
                                class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right"
                            >
                                Clicks / Conv.
                            </th>
                            <th
                                class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right"
                            >
                                Revenue
                            </th>
                            <th
                                class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        <tr v-if="program.links.length === 0">
                            <td
                                colspan="6"
                                class="p-8 text-center text-sm text-gray-500"
                            >
                                No links generated yet. Create one to start
                                tracking referrals.
                            </td>
                        </tr>
                        <tr
                            v-for="link in program.links"
                            :key="link.id"
                            class="hover:bg-white/[0.02] transition-colors"
                        >
                            <td class="p-4">
                                <p class="text-sm font-medium text-white">
                                    {{ link.affiliate_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ link.affiliate_email }}
                                </p>
                            </td>
                            <td class="p-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-white/[0.05] text-cyan-300"
                                >
                                    {{ link.offer_title }}
                                </span>
                            </td>
                            <td class="p-4">
                                <code
                                    class="text-xs text-gray-400 bg-black/50 px-2 py-1 rounded"
                                    >{{ link.code }}</code
                                >
                            </td>
                            <td class="p-4 text-right">
                                <div class="text-sm">
                                    <span class="text-white">{{
                                        link.clicks_count
                                    }}</span>
                                    <span class="text-gray-600 mx-1">/</span>
                                    <span class="text-emerald-400">{{
                                        link.conversions_count
                                    }}</span>
                                </div>
                            </td>
                            <td
                                class="p-4 text-right text-sm font-medium text-white"
                            >
                                {{ formatCurrency(link.revenue) }}
                            </td>
                            <td class="p-4 text-right">
                                <button
                                    @click="copyLink(link.url)"
                                    class="p-1.5 text-gray-400 hover:text-cyan-400 hover:bg-white/[0.08] rounded transition-colors"
                                    title="Copy Link"
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
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                        />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
