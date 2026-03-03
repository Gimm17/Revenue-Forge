<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    order: Object,
    offer: Object,
    customerEmail: String,
});

const form = useForm({});

const submit = () => {
    form.post(route("checkout.demo.process", props.order.id));
};
</script>

<template>
    <div
        class="min-h-screen bg-gray-950 flex flex-col items-center justify-center p-4"
    >
        <Head title="Mock Checkout - RevenueForge" />

        <!-- Mock Mayar Interface -->
        <div
            class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden relative"
        >
            <!-- Sandbox Banner -->
            <div
                class="bg-amber-500 text-black text-xs font-bold text-center py-1.5 uppercase tracking-wide"
            >
                Sandbox / Test Mode
            </div>

            <div class="p-8">
                <!-- Branding -->
                <div class="flex items-center justify-between mb-8">
                    <div
                        class="font-bold text-gray-900 text-lg flex items-center gap-2"
                    >
                        <div
                            class="w-6 h-6 rounded bg-gradient-to-br from-cyan-500 to-violet-600"
                        ></div>
                        RevenueForge
                    </div>
                </div>

                <!-- Order Details -->
                <div class="mb-8">
                    <p class="text-sm font-medium text-gray-500 mb-1">
                        Total Payment
                    </p>
                    <h1 class="text-4xl font-extrabold text-gray-900">
                        {{ order.formattedAmount }}
                    </h1>
                    <p
                        class="text-sm text-gray-600 mt-3 pt-3 border-t border-gray-100 flex justify-between"
                    >
                        <span>{{ offer.title }}</span>
                        <span>1x</span>
                    </p>
                </div>

                <!-- Customer Details -->
                <div class="bg-gray-50 rounded-xl p-4 mb-8">
                    <p class="text-xs text-gray-500 font-medium mb-1">
                        Customer
                    </p>
                    <p class="text-sm text-gray-900 font-medium">
                        {{ customerEmail }}
                    </p>
                </div>

                <!-- Simulated Payment Methods -->
                <div class="mb-8 space-y-3">
                    <div
                        class="flex items-center justify-between p-4 border-2 border-cyan-500 rounded-xl bg-cyan-50 cursor-pointer"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-4 h-4 rounded-full border-4 border-cyan-500 flex-shrink-0"
                            ></div>
                            <span class="font-medium text-gray-900"
                                >Test Success Payment</span
                            >
                        </div>
                        <svg
                            class="w-5 h-5 text-cyan-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                    </div>
                </div>

                <!-- Action -->
                <form @submit.prevent="submit">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold shadow-lg shadow-gray-900/20 transition-all disabled:opacity-50"
                    >
                        {{
                            form.processing
                                ? "Processing..."
                                : "Simulate Payment"
                        }}
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <Link
                        :href="route('checkout.cancel')"
                        class="text-xs text-gray-500 hover:text-gray-900 transition-colors"
                    >
                        Cancel and return to store
                    </Link>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="bg-gray-50 py-4 text-center text-xs text-gray-400 font-medium border-t border-gray-100"
            >
                Secured by Mock Gateway
            </div>
        </div>

        <p class="mt-8 text-sm text-gray-500 max-w-md text-center">
            You are seeing this page because the Mayar API key is not set in
            <code class="bg-gray-800 px-1 rounded text-gray-300">.env</code>.
            This simulator completely tests the Webhook workflow end-to-end.
        </p>
    </div>
</template>
