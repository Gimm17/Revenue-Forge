<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted } from "vue";
import axios from "axios";
import SectionRenderer from "@/Components/LandingBuilder/SectionRenderer.vue";
import ChatWidget from "@/Components/ChatWidget.vue";

const props = defineProps({
    offer: Object,
    workspace: Object,
    sections: { type: Array, default: () => [] },
    testimonials: { type: Array, default: () => [] },
    midtrans_client_key: { type: String, default: "" },
    midtrans_snap_url: {
        type: String,
        default: "https://app.sandbox.midtrans.com/snap/snap.js",
    },
});

// ── State ──────────────────────────────────────────────────────────────────
const showCheckout = ref(false);
const isLoading = ref(false);
const errorMsg = ref("");
const form = ref({ name: "", email: "", phone: "" });
const formErrors = ref({});
let snapScriptEl = null;

// ── Load Midtrans Snap JS ─────────────────────────────────────────────────
onMounted(() => {
    if (props.midtrans_client_key && !document.getElementById("midtrans-snap-js")) {
        snapScriptEl = document.createElement("script");
        snapScriptEl.id = "midtrans-snap-js";
        snapScriptEl.src = props.midtrans_snap_url;
        snapScriptEl.setAttribute("data-client-key", props.midtrans_client_key);
        document.head.appendChild(snapScriptEl);
    }
});

onUnmounted(() => {
    // Don't remove the script — it may be reused
});

// ── Submit checkout ───────────────────────────────────────────────────────
const submit = async () => {
    formErrors.value = {};
    errorMsg.value = "";

    // Basic validation
    if (!form.value.name.trim()) {
        formErrors.value.name = "Nama wajib diisi";
        return;
    }
    if (!form.value.email.trim()) {
        formErrors.value.email = "Email wajib diisi";
        return;
    }

    isLoading.value = true;

    try {
        const response = await axios.post(
            route("offer.checkout", props.offer.slug),
            {
                name: form.value.name,
                email: form.value.email,
                phone: form.value.phone,
            }
        );

        const { snap_token, redirect_url } = response.data;

        if (!snap_token) {
            errorMsg.value = "Gagal mendapatkan token pembayaran.";
            isLoading.value = false;
            return;
        }

        // Close our modal then open Midtrans Snap popup
        showCheckout.value = false;

        if (typeof window.snap === "undefined") {
            // Snap JS not loaded yet — fallback to redirect_url
            if (redirect_url) {
                window.location.href = redirect_url;
            } else {
                errorMsg.value =
                    "Skrip Midtrans belum termuat. Coba refresh halaman.";
            }
            isLoading.value = false;
            return;
        }

        window.snap.pay(snap_token, {
            onSuccess: (result) => {
                // Payment successful → redirect to success page
                router.visit(route("checkout.success"), {
                    method: "get",
                });
            },
            onPending: (result) => {
                // Payment pending (e.g. VA / bank transfer)
                router.visit(route("checkout.success") + "?status=pending", {
                    method: "get",
                });
            },
            onError: (result) => {
                errorMsg.value =
                    "Pembayaran gagal. Silakan coba metode lain.";
                showCheckout.value = true;
            },
            onClose: () => {
                // User closed the Snap popup without completing
                isLoading.value = false;
                showCheckout.value = true;
            },
        });
    } catch (err) {
        const errors = err.response?.data?.errors;
        if (errors) {
            formErrors.value = Object.fromEntries(
                Object.entries(errors).map(([k, v]) => [k, v[0]])
            );
        } else {
            errorMsg.value =
                err.response?.data?.error ||
                "Terjadi kesalahan. Silakan coba lagi.";
        }
    } finally {
        isLoading.value = false;
    }
};

// ── Helpers ───────────────────────────────────────────────────────────────
const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: props.offer.currency || "IDR",
        minimumFractionDigits: 0,
    }).format(amount || 0);

const sortedSections = computed(() =>
    [...props.sections].sort((a, b) => a.order - b.order)
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
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border border-cyan-500/30 text-[#00D261] bg-cyan-500/10 mb-6"
                        >
                            {{ offer.type_label }}
                        </span>
                        <h1
                            class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight"
                        >
                            {{ offer.title }}
                        </h1>
                        <p
                            v-if="offer.tagline"
                            class="text-lg md:text-xl text-gray-500 mb-8"
                        >
                            {{ offer.tagline }}
                        </p>
                        <div
                            class="flex items-center justify-center gap-4 mb-8"
                        >
                            <span
                                class="text-4xl md:text-5xl font-bold text-gray-900"
                                >{{ formatCurrency(offer.price) }}</span
                            >
                        </div>
                        <button
                            @click="showCheckout = true"
                            class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-xl hover:scale-105 transition-all shadow-xl shadow-[#25f47b]/30 hover:shadow-[#25f47b]/40 hover:scale-[1.02]"
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
                    <p class="text-lg text-gray-900 leading-relaxed">
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
                            class="flex items-start gap-3 p-4 rounded-xl bg-white border border-gray-100 shadow-xl ring-1 ring-black/5"
                        >
                            <svg
                                class="w-5 h-5 text-[#00D261] mt-0.5 shrink-0"
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
                        class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-xl hover:scale-105 transition-all shadow-xl shadow-[#25f47b]/30"
                    >
                        {{ offer.cta_text || "Buy Now" }} —
                        {{ formatCurrency(offer.price) }}
                    </button>
                </section>
            </template>

            <!-- ── Checkout Modal ──────────────────────────────────────── -->
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
                        <!-- Close -->
                        <button
                            @click="showCheckout = false"
                            class="absolute top-4 right-4 text-gray-500 hover:text-gray-300 transition-colors"
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

                        <!-- Header -->
                        <h3 class="text-lg font-bold text-white mb-1">
                            Complete Your Purchase
                        </h3>
                        <p class="text-sm text-gray-400 mb-5">
                            {{ offer.title }} —
                            <span class="text-[#00D261] font-semibold">{{
                                formatCurrency(offer.price)
                            }}</span>
                        </p>

                        <!-- Error banner -->
                        <div
                            v-if="errorMsg"
                            class="mb-4 px-4 py-3 rounded-lg bg-red-500/15 border border-red-500/30 text-red-400 text-sm"
                        >
                            {{ errorMsg }}
                        </div>

                        <!-- Form -->
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
                                    :disabled="isLoading"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm focus:border-[#00D261]/50 focus:outline-none disabled:opacity-50"
                                    placeholder="Your full name"
                                />
                                <p
                                    v-if="formErrors.name"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ formErrors.name }}
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
                                    :disabled="isLoading"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm focus:border-[#00D261]/50 focus:outline-none disabled:opacity-50"
                                    placeholder="email@example.com"
                                />
                                <p
                                    v-if="formErrors.email"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ formErrors.email }}
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
                                    :disabled="isLoading"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm focus:border-[#00D261]/50 focus:outline-none disabled:opacity-50"
                                    placeholder="08123456789"
                                />
                            </div>
                            <button
                                type="submit"
                                :disabled="isLoading"
                                class="w-full py-3 text-sm font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-xl hover:scale-[1.02] transition-all disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                <svg
                                    v-if="isLoading"
                                    class="w-4 h-4 animate-spin"
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
                                    />
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v8H4z"
                                    />
                                </svg>
                                <span>
                                    {{
                                        isLoading
                                            ? "Memproses..."
                                            : `Bayar ${formatCurrency(offer.price)}`
                                    }}
                                </span>
                            </button>
                        </form>

                        <!-- Footer -->
                        <p class="text-xs text-gray-600 mt-4 text-center">
                            Pembayaran diproses melalui
                            <strong class="text-gray-400">Midtrans</strong>
                            — aman &amp; terenkripsi
                        </p>
                    </div>
                </div>
            </Teleport>
        </div>

        <!-- Testimonials Section -->
        <section
            v-if="testimonials && testimonials.length > 0"
            class="max-w-4xl mx-auto px-6 py-12"
        >
            <h2
                class="text-2xl font-bold text-center mb-8"
                :style="{ color: workspace.brand_color }"
            >
                What Our Customers Say
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="(t, i) in testimonials"
                    :key="i"
                    class="bg-white rounded-xl p-5 shadow-sm border border-gray-100"
                >
                    <div class="text-sm mb-2">{{ "⭐".repeat(t.rating) }}</div>
                    <p
                        class="text-gray-700 text-sm italic leading-relaxed mb-3"
                    >
                        "{{ t.content }}"
                    </p>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ t.name }}
                        </p>
                        <p v-if="t.role" class="text-xs text-gray-500">
                            {{ t.role }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

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
