<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ programs: Array });

const showCreate = ref(false);
const form = useForm({
    name: "",
    commission_type: "percentage",
    commission_value: 10,
    cookie_days: 30,
});

const submit = () => {
    form.post(route("app.affiliates.store"), {
        onSuccess: () => {
            showCreate.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Affiliates" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Affiliate Programs</h1>
        </template>

        <template #actions>
            <button
                @click="showCreate = !showCreate"
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
                New Program
            </button>
        </template>

        <!-- Create Form -->
        <div
            v-if="showCreate"
            class="mb-6 rounded-xl bg-gradient-to-br from-cyan-500/[0.06] to-violet-500/[0.06] border border-cyan-500/20 p-6"
        >
            <h2 class="text-sm font-semibold text-white mb-4">
                Create Affiliate Program
            </h2>
            <form
                @submit.prevent="submit"
                class="grid grid-cols-1 sm:grid-cols-2 gap-4"
            >
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Program Name</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="Referral Program"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Commission Type</label
                    >
                    <select
                        v-model="form.commission_type"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    >
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Commission Value</label
                    >
                    <input
                        v-model.number="form.commission_value"
                        type="number"
                        min="1"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Cookie Duration (days)</label
                    >
                    <input
                        v-model.number="form.cookie_days"
                        type="number"
                        min="1"
                        max="365"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div class="sm:col-span-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all disabled:opacity-50"
                    >
                        {{ form.processing ? "Creating..." : "Create Program" }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Programs list -->
        <div
            v-if="programs && programs.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 gap-4"
        >
            <Link
                v-for="p in programs"
                :key="p.id"
                :href="route('app.affiliates.show', p.id)"
                class="block rounded-xl bg-white/[0.04] border border-white/[0.08] p-5 hover:border-cyan-500/30 transition-all group"
            >
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-base font-semibold text-white">
                        {{ p.name }}
                    </h3>
                    <span
                        :class="[
                            p.is_active
                                ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                : 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                            'text-xs px-2 py-0.5 rounded-full border',
                        ]"
                    >
                        {{ p.is_active ? "Active" : "Inactive" }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-lg font-bold text-cyan-400">
                            {{ p.commission_value
                            }}{{
                                p.commission_type === "percentage" ? "%" : ""
                            }}
                        </p>
                        <p class="text-xs text-gray-500">Commission</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-white">
                            {{ p.links_count }}
                        </p>
                        <p class="text-xs text-gray-500">Links</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-white">—</p>
                        <p class="text-xs text-gray-500">Conversions</p>
                    </div>
                </div>
            </Link>
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
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                />
            </svg>
            <h3 class="text-lg font-semibold text-white mb-2">
                No affiliate programs
            </h3>
            <p class="text-sm text-gray-500">
                Create your first affiliate program to track referrals and
                commissions.
            </p>
        </div>
    </AppLayout>
</template>
