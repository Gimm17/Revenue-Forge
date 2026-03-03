<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import SectionRenderer from "@/Components/LandingBuilder/SectionRenderer.vue";
import ChatWidget from "@/Components/ChatWidget.vue";

const props = defineProps({
    offer: Object,
    workspace: Object,
    sections: { type: Array, default: () => [] },
});

const showCheckout = ref(false);
const form = useForm({ name: "", email: "", phone: "" });

const submit = () => {
    form.post(route("offer.checkout", props.offer.slug));
};

const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: props.offer.currency || "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);

// Sort sections by order
const sortedSections = computed(() =>
    [...props.sections].sort((a, b) => a.order - b.order),
);
</script>

<template>
    <Head :title="offer.title" />
    <PublicLayout>
        <div class="min-h-screen">
            <!-- Dynamic Sections from Builder -->
            <template v-if="sections.length">
                <SectionRenderer
                    v-for="section in sortedSections"
                    :key="section.id"
                    :section="section"
                    :offer="offer"
                    @checkout="showCheckout = true"
                />
            </template>

            <!-- Fallback: Original hardcoded layout -->
            <template v-else>
                <!-- Hero -->
                <section class="relative py-20 md:py-28 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-violet-500/5"
                    />
                    <div class="relative max-w-4xl mx-auto px-6 text-center">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border border-cyan-500/30 text-cyan-400 bg-cyan-500/10 mb-6"
                        >
                            {{ offer.type_label }}
                        </span>
                        <h1
                            class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight"
                        >
                            {{ offer.title }}
                        </h1>
                        <p
                            v-if="offer.tagline"
                            class="text-lg md:text-xl text-gray-400 mb-8"
                        >
                            {{ offer.tagline }}
                        </p>
                        <div
                            class="flex items-center justify-center gap-4 mb-8"
                        >
                            <span
                                class="text-4xl md:text-5xl font-bold text-white"
                                >{{ formatCurrency(offer.price) }}</span
                            >
                        </div>
                        <button
                            @click="showCheckout = true"
                            class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40 hover:scale-[1.02]"
                        >
                            {{ offer.cta_text || "Buy Now" }}
                        </button>
                    </div>
                </section>

                <!-- Short Pitch -->
                <section
                    v-if="offer.short_pitch"
                    class="max-w-2xl mx-auto px-6 py-12 text-center"
                >
                    <p class="text-lg text-gray-300 leading-relaxed">
                        {{ offer.short_pitch }}
                    </p>
                </section>

                <!-- Benefits -->
                <section
                    v-if="offer.benefits?.length"
                    class="max-w-4xl mx-auto px-6 py-12"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="(b, i) in offer.benefits"
                            :key="i"
                            class="flex items-start gap-3 p-4 rounded-xl bg-white/[0.04] border border-white/[0.08]"
                        >
                            <svg
                                class="w-5 h-5 text-cyan-400 mt-0.5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span class="text-gray-200 text-sm">{{ b }}</span>
                        </div>
                    </div>
                </section>

                <!-- Bottom CTA -->
                <section class="text-center py-8 pb-20">
                    <button
                        @click="showCheckout = true"
                        class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-xl shadow-cyan-500/25"
                    >
                        {{ offer.cta_text || "Buy Now" }} —
                        {{ formatCurrency(offer.price) }}
                    </button>
                </section>
            </template>

            <!-- Checkout Modal -->
            <Teleport to="body">
                <div
                    v-if="showCheckout"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                        @click="showCheckout = false"
                    />
                    <div
                        class="relative w-full max-w-md rounded-2xl bg-gray-900 border border-white/10 p-6 shadow-2xl"
                    >
                        <button
                            @click="showCheckout = false"
                            class="absolute top-4 right-4 text-gray-500 hover:text-white"
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
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                        <h3 class="text-lg font-bold text-white mb-1">
                            Complete Your Purchase
                        </h3>
                        <p class="text-sm text-gray-500 mb-5">
                            {{ offer.title }} —
                            {{ formatCurrency(offer.price) }}
                        </p>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-400 mb-1"
                                    >Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                    placeholder="Your full name"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-400 mb-1"
                                    >Email</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                    placeholder="email@example.com"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-400 mb-1"
                                    >Phone (optional)</label
                                >
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                    placeholder="08123456789"
                                />
                            </div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full py-3 text-sm font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all disabled:opacity-50"
                            >
                                {{
                                    form.processing
                                        ? "Processing..."
                                        : `Pay ${formatCurrency(offer.price)}`
                                }}
                            </button>
                        </form>
                        <p class="text-xs text-gray-600 mt-4 text-center">
                            Pembayaran diproses melalui
                            <strong class="text-gray-500">Mayar</strong>
                        </p>
                    </div>
                </div>
            </Teleport>
        </div>

        <!-- Chat Widget -->
        <ChatWidget
            :enabled="workspace.chat_widget_enabled"
            :greeting="workspace.chat_greeting"
            :email="workspace.chat_email"
            :brand-color="workspace.brand_color"
            :secondary-color="workspace.secondary_color"
            :workspace-name="workspace.name"
        />
    </PublicLayout>
</template>
