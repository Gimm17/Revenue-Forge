<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ offerTypes: Array });

const form = useForm({
    title: "",
    type: "one_time",
    tagline: "",
    short_pitch: "",
    long_copy: "",
    benefits: [""],
    faq: [{ question: "", answer: "" }],
    cta_text: "Buy Now",
    price: 0,
    currency: "IDR",
    interval: null,
    credit_amount: null,
    delivery_type: "none",
    delivery_content: "",
    delivery_label: "",
});

// AI Generation
const aiForm = ref({
    business_type: "",
    target_audience: "",
    offer_type: "one_time",
    goal: "",
    price_range: "",
    tone: "professional",
    cta_style: "direct",
});
const generating = ref(false);
const aiResult = ref(null);

const generateWithAI = async () => {
    generating.value = true;
    aiResult.value = null;
    try {
        const response = await fetch(route("app.offers.generate"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
                Accept: "application/json",
            },
            body: JSON.stringify(aiForm.value),
        });
        const data = await response.json();

        if (!data.success) {
            alert(data.error || "AI generation failed.");
            return;
        }

        aiResult.value = data.data;
        applyAiResult();
    } catch (e) {
        console.error("AI generation exception", e);
        alert("An error occurred while communicating with the AI server.");
    } finally {
        generating.value = false;
    }
};

const applyAiResult = () => {
    if (!aiResult.value) return;
    const r = aiResult.value;
    form.title = r.title || form.title;
    form.tagline = r.tagline || form.tagline;
    form.short_pitch = r.short_pitch || form.short_pitch;
    form.long_copy = r.long_copy || form.long_copy;
    form.benefits = r.benefits?.length ? r.benefits : form.benefits;
    form.faq = r.faq?.length ? r.faq : form.faq;
    form.cta_text = r.cta || form.cta_text;
};

const addBenefit = () => form.benefits.push("");
const removeBenefit = (i) => form.benefits.splice(i, 1);
const addFaq = () => form.faq.push({ question: "", answer: "" });
const removeFaq = (i) => form.faq.splice(i, 1);

const submit = () => {
    form.benefits = form.benefits.filter((b) => b.trim());
    form.faq = form.faq.filter((f) => f.question.trim());
    form.post(route("app.offers.store"));
};
</script>

<template>
    <Head title="Create Offer" />

    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Create Offer</h1>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left: Offer Form (3 cols) -->
            <div class="lg:col-span-3 space-y-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Basic Info -->
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-4"
                    >
                        <h2
                            class="text-sm font-semibold text-gray-300 uppercase tracking-wider"
                        >
                            Basic Information
                        </h2>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                placeholder="e.g. Template Bundle Pro"
                            />
                            <p
                                v-if="form.errors.title"
                                class="text-red-400 text-xs mt-1"
                            >
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-400 mb-1"
                                    >Offer Type</label
                                >
                                <select
                                    v-model="form.type"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                >
                                    <option
                                        v-for="t in offerTypes"
                                        :key="t.value"
                                        :value="t.value"
                                    >
                                        {{ t.label }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-400 mb-1"
                                    >Price (IDR)</label
                                >
                                <input
                                    v-model.number="form.price"
                                    type="number"
                                    min="0"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                />
                            </div>
                        </div>

                        <div
                            v-if="
                                form.type === 'subscription' ||
                                form.type === 'saas_plan'
                            "
                        >
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Interval</label
                            >
                            <select
                                v-model="form.interval"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            >
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>

                        <div v-if="form.type === 'credit_pack'">
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Credit Amount</label
                            >
                            <input
                                v-model.number="form.credit_amount"
                                type="number"
                                min="0"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Tagline</label
                            >
                            <input
                                v-model="form.tagline"
                                type="text"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                placeholder="A punchy one-liner"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Short Pitch</label
                            >
                            <textarea
                                v-model="form.short_pitch"
                                rows="2"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none resize-none"
                                placeholder="2-3 sentence elevator pitch"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >CTA Text</label
                            >
                            <input
                                v-model="form.cta_text"
                                type="text"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                placeholder="Buy Now"
                            />
                        </div>
                    </div>

                    <!-- Digital Delivery -->
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-4"
                    >
                        <h2
                            class="text-sm font-semibold text-gray-300 uppercase tracking-wider"
                        >
                            📦 Digital Delivery
                        </h2>
                        <p class="text-xs text-gray-500">
                            Automatically deliver content to buyers after
                            payment.
                        </p>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Delivery Type</label
                            >
                            <select
                                v-model="form.delivery_type"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                            >
                                <option value="none">No Delivery</option>
                                <option value="file_url">
                                    File / Download URL
                                </option>
                                <option value="redirect_url">
                                    Access Link / Redirect
                                </option>
                                <option value="text_content">
                                    Text / Instructions
                                </option>
                            </select>
                        </div>

                        <template v-if="form.delivery_type !== 'none'">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-400 mb-1"
                                    >Button / Section Label</label
                                >
                                <input
                                    v-model="form.delivery_label"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                    :placeholder="
                                        form.delivery_type === 'file_url'
                                            ? 'Download Your Ebook'
                                            : form.delivery_type ===
                                                'redirect_url'
                                              ? 'Access Your Course'
                                              : 'Your Instructions'
                                    "
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-400 mb-1"
                                    >{{
                                        form.delivery_type === "file_url"
                                            ? "Download URL"
                                            : form.delivery_type ===
                                                "redirect_url"
                                              ? "Access URL"
                                              : "Delivery Content"
                                    }}</label
                                >
                                <input
                                    v-if="form.delivery_type !== 'text_content'"
                                    v-model="form.delivery_content"
                                    type="url"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                    :placeholder="
                                        form.delivery_type === 'file_url'
                                            ? 'https://drive.google.com/file/...'
                                            : 'https://your-course.com/access'
                                    "
                                />
                                <textarea
                                    v-else
                                    v-model="form.delivery_content"
                                    rows="4"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                    placeholder="Instructions, license keys, access details..."
                                />
                            </div>
                        </template>
                    </div>

                    <!-- Benefits -->
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-3"
                    >
                        <div class="flex items-center justify-between">
                            <h2
                                class="text-sm font-semibold text-gray-300 uppercase tracking-wider"
                            >
                                Benefits
                            </h2>
                            <button
                                type="button"
                                @click="addBenefit"
                                class="text-xs text-cyan-400 hover:text-cyan-300"
                            >
                                + Add
                            </button>
                        </div>
                        <div
                            v-for="(_, i) in form.benefits"
                            :key="i"
                            class="flex gap-2"
                        >
                            <input
                                v-model="form.benefits[i]"
                                type="text"
                                class="flex-1 px-4 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                :placeholder="`Benefit ${i + 1}`"
                            />
                            <button
                                v-if="form.benefits.length > 1"
                                type="button"
                                @click="removeBenefit(i)"
                                class="px-2 text-gray-500 hover:text-red-400"
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
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-3"
                    >
                        <div class="flex items-center justify-between">
                            <h2
                                class="text-sm font-semibold text-gray-300 uppercase tracking-wider"
                            >
                                FAQ
                            </h2>
                            <button
                                type="button"
                                @click="addFaq"
                                class="text-xs text-cyan-400 hover:text-cyan-300"
                            >
                                + Add
                            </button>
                        </div>
                        <div
                            v-for="(_, i) in form.faq"
                            :key="i"
                            class="space-y-2 p-3 bg-white/[0.02] rounded-lg border border-white/[0.04]"
                        >
                            <input
                                v-model="form.faq[i].question"
                                type="text"
                                class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                placeholder="Question"
                            />
                            <textarea
                                v-model="form.faq[i].answer"
                                rows="2"
                                class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
                                placeholder="Answer"
                            />
                            <button
                                v-if="form.faq.length > 1"
                                type="button"
                                @click="removeFaq(i)"
                                class="text-xs text-gray-500 hover:text-red-400"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
                    >
                        {{ form.processing ? "Saving..." : "Create Offer" }}
                    </button>
                </form>
            </div>

            <!-- Right: AI Generation Panel (2 cols) -->
            <div class="lg:col-span-2">
                <div
                    class="sticky top-24 rounded-xl bg-gradient-to-br from-cyan-500/[0.06] to-violet-500/[0.06] border border-cyan-500/20 p-6 space-y-4"
                >
                    <div class="flex items-center gap-2 mb-2">
                        <svg
                            class="w-5 h-5 text-cyan-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                            />
                        </svg>
                        <h2 class="text-sm font-semibold text-white">
                            AI Offer Studio
                        </h2>
                    </div>

                    <div class="space-y-3">
                        <input
                            v-model="aiForm.business_type"
                            type="text"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            placeholder="Business Type (e.g. Digital Agency)"
                        />
                        <input
                            v-model="aiForm.target_audience"
                            type="text"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            placeholder="Target Audience (e.g. Freelancers)"
                        />
                        <select
                            v-model="aiForm.offer_type"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                        >
                            <option
                                v-for="t in offerTypes"
                                :key="t.value"
                                :value="t.value"
                            >
                                {{ t.label }}
                            </option>
                        </select>
                        <input
                            v-model="aiForm.goal"
                            type="text"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            placeholder="Goal (e.g. Increase monthly revenue)"
                        />
                        <input
                            v-model="aiForm.price_range"
                            type="text"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            placeholder="Price Range (e.g. IDR 99K - 299K)"
                        />
                        <div class="grid grid-cols-2 gap-2">
                            <select
                                v-model="aiForm.tone"
                                class="px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            >
                                <option value="professional">
                                    Professional
                                </option>
                                <option value="casual">Casual</option>
                                <option value="bold">Bold</option>
                                <option value="friendly">Friendly</option>
                            </select>
                            <select
                                v-model="aiForm.cta_style"
                                class="px-3 py-2 bg-white/[0.06] border border-white/[0.08] rounded-lg text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            >
                                <option value="direct">Direct</option>
                                <option value="soft">Soft</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <button
                        @click="generateWithAI"
                        :disabled="
                            generating ||
                            !aiForm.business_type ||
                            !aiForm.target_audience ||
                            !aiForm.goal
                        "
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all disabled:opacity-50"
                    >
                        <svg
                            v-if="generating"
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
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                            />
                        </svg>
                        {{ generating ? "Generating..." : "Generate with AI" }}
                    </button>

                    <!-- AI Result Preview -->
                    <div
                        v-if="aiResult"
                        class="mt-4 p-4 bg-white/[0.04] rounded-lg border border-white/[0.08] space-y-3"
                    >
                        <h3 class="text-sm font-semibold text-cyan-400">
                            AI Generated
                        </h3>
                        <div class="space-y-2 text-xs text-gray-300">
                            <p>
                                <strong class="text-white">Title:</strong>
                                {{ aiResult.title }}
                            </p>
                            <p>
                                <strong class="text-white">Tagline:</strong>
                                {{ aiResult.tagline }}
                            </p>
                            <p>
                                <strong class="text-white">Pitch:</strong>
                                {{ aiResult.short_pitch }}
                            </p>
                            <p v-if="aiResult.pricing_suggestion">
                                <strong class="text-white">Pricing:</strong>
                                {{ aiResult.pricing_suggestion }}
                            </p>
                        </div>
                        <button
                            @click="applyAiResult"
                            class="w-full px-4 py-2 text-xs font-semibold text-cyan-400 border border-cyan-500/30 rounded-lg hover:bg-cyan-500/10 transition-colors"
                        >
                            Apply to Offer Form →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
