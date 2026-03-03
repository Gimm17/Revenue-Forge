<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    coupons: Array,
    offers: Array,
});

const showCreate = ref(false);

const form = useForm({
    code: "",
    type: "percentage",
    value: 10,
    offer_id: "",
    max_uses: "",
    starts_at: "",
    expires_at: "",
});

const generateCode = async () => {
    try {
        const res = await fetch(route("app.coupons.generate"));
        const data = await res.json();
        form.code = data.code;
    } catch (e) {
        form.code = Math.random().toString(36).substring(2, 10).toUpperCase();
    }
};

const submit = () => {
    form.post(route("app.coupons.store"), {
        onSuccess: () => {
            showCreate.value = false;
            form.reset();
        },
    });
};

const toggleCoupon = (id) => {
    router.post(route("app.coupons.toggle", id));
};

const deleteCoupon = (id) => {
    if (confirm("Delete this coupon?")) {
        router.delete(route("app.coupons.destroy", id));
    }
};

const statusColors = {
    Active: "bg-emerald-500/20 text-emerald-400",
    Inactive: "bg-gray-500/20 text-gray-400",
    Expired: "bg-red-500/20 text-red-400",
    Exhausted: "bg-amber-500/20 text-amber-400",
};
</script>

<template>
    <Head title="Coupons" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Coupons & Discounts</h1>
        </template>

        <template #actions>
            <button
                @click="showCreate = !showCreate"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
            >
                <span v-if="!showCreate">+ New Coupon</span>
                <span v-else>✕ Cancel</span>
            </button>
        </template>

        <!-- Create Form -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="showCreate"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 mb-6"
            >
                <h2 class="text-sm font-semibold text-white mb-4">
                    Create New Coupon
                </h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Code -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Coupon Code</label
                            >
                            <div class="flex gap-2">
                                <input
                                    v-model="form.code"
                                    type="text"
                                    class="flex-1 px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm uppercase focus:border-cyan-500/50 focus:outline-none"
                                    placeholder="SUMMER20"
                                />
                                <button
                                    type="button"
                                    @click="generateCode"
                                    class="px-3 py-2 text-xs text-cyan-400 border border-cyan-500/30 rounded-lg hover:bg-cyan-500/10 transition-colors"
                                >
                                    🎲 Generate
                                </button>
                            </div>
                            <p
                                v-if="form.errors.code"
                                class="text-red-400 text-xs mt-1"
                            >
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <!-- Offer -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Applies To</label
                            >
                            <select
                                v-model="form.offer_id"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            >
                                <option value="">All Offers</option>
                                <option
                                    v-for="o in offers"
                                    :key="o.id"
                                    :value="o.id"
                                >
                                    {{ o.title }}
                                </option>
                            </select>
                        </div>

                        <!-- Type -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Discount Type</label
                            >
                            <div class="flex gap-2">
                                <button
                                    type="button"
                                    @click="form.type = 'percentage'"
                                    :class="[
                                        'flex-1 py-2.5 text-sm font-medium rounded-lg border transition-all',
                                        form.type === 'percentage'
                                            ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30'
                                            : 'bg-white/[0.02] text-gray-500 border-white/[0.06]',
                                    ]"
                                >
                                    % Percentage
                                </button>
                                <button
                                    type="button"
                                    @click="form.type = 'fixed'"
                                    :class="[
                                        'flex-1 py-2.5 text-sm font-medium rounded-lg border transition-all',
                                        form.type === 'fixed'
                                            ? 'bg-violet-500/10 text-violet-400 border-violet-500/30'
                                            : 'bg-white/[0.02] text-gray-500 border-white/[0.06]',
                                    ]"
                                >
                                    💰 Fixed Amount
                                </button>
                            </div>
                        </div>

                        <!-- Value -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                            >
                                {{
                                    form.type === "percentage"
                                        ? "Discount (%)"
                                        : "Discount (Rp)"
                                }}
                            </label>
                            <input
                                v-model.number="form.value"
                                type="number"
                                :min="1"
                                :max="
                                    form.type === 'percentage' ? 100 : 99999999
                                "
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            />
                        </div>

                        <!-- Max Uses -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Max Uses</label
                            >
                            <input
                                v-model.number="form.max_uses"
                                type="number"
                                min="1"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                placeholder="Unlimited"
                            />
                        </div>

                        <!-- Expires At -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Expires At</label
                            >
                            <input
                                v-model="form.expires_at"
                                type="date"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
                    >
                        {{ form.processing ? "Creating..." : "Create Coupon" }}
                    </button>
                </form>
            </div>
        </transition>

        <!-- Coupons List -->
        <div
            v-if="coupons && coupons.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.06]">
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                            >
                                Code
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                            >
                                Discount
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                            >
                                Offer
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase"
                            >
                                Used
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase"
                            >
                                Status
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                            >
                                Expires
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="c in coupons"
                            :key="c.id"
                            class="border-b border-white/[0.04] hover:bg-white/[0.02] transition-colors"
                        >
                            <td class="px-4 py-3">
                                <span
                                    class="font-mono text-sm text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded"
                                    >{{ c.code }}</span
                                >
                            </td>
                            <td
                                class="px-4 py-3 text-sm text-white font-medium"
                            >
                                {{ c.discount_label }}
                            </td>
                            <td
                                class="px-4 py-3 text-sm text-gray-400 truncate max-w-[150px]"
                            >
                                {{ c.offer }}
                            </td>
                            <td
                                class="px-4 py-3 text-center text-sm text-gray-300"
                            >
                                {{ c.used_count
                                }}<span class="text-gray-600">{{
                                    c.max_uses ? "/" + c.max_uses : ""
                                }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    :class="[
                                        'text-[10px] font-semibold px-2 py-0.5 rounded-full',
                                        statusColors[c.status],
                                    ]"
                                >
                                    {{ c.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ c.expires_at || "—" }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <button
                                        @click="toggleCoupon(c.id)"
                                        class="p-1.5 text-gray-500 hover:text-amber-400 rounded-lg hover:bg-white/[0.06] transition-colors"
                                        :title="
                                            c.is_active
                                                ? 'Deactivate'
                                                : 'Activate'
                                        "
                                    >
                                        {{ c.is_active ? "⏸️" : "▶️" }}
                                    </button>
                                    <button
                                        @click="deleteCoupon(c.id)"
                                        class="p-1.5 text-gray-500 hover:text-red-400 rounded-lg hover:bg-white/[0.06] transition-colors"
                                        title="Delete"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-12 text-center"
        >
            <div class="text-5xl mb-4">🎟️</div>
            <h3 class="text-lg font-semibold text-white mb-2">
                No Coupons Yet
            </h3>
            <p class="text-sm text-gray-500 mb-4">
                Create your first discount coupon to boost conversions.
            </p>
            <button
                @click="showCreate = true"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all"
            >
                + Create First Coupon
            </button>
        </div>
    </AppLayout>
</template>
