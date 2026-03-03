<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    offer: Object,
    versions: Array,
    offerTypes: Array,
});

const form = useForm({
    title: props.offer.title,
    type: props.offer.type,
    tagline: props.offer.tagline || "",
    short_pitch: props.offer.short_pitch || "",
    long_copy: props.offer.long_copy || "",
    benefits: props.offer.benefits?.length ? props.offer.benefits : [""],
    faq: props.offer.faq?.length
        ? props.offer.faq
        : [{ question: "", answer: "" }],
    cta_text: props.offer.cta_text || "Buy Now",
    price: props.offer.price,
    currency: props.offer.currency || "IDR",
    interval: props.offer.interval,
    credit_amount: props.offer.credit_amount,
});

const addBenefit = () => form.benefits.push("");
const removeBenefit = (i) => form.benefits.splice(i, 1);
const addFaq = () => form.faq.push({ question: "", answer: "" });
const removeFaq = (i) => form.faq.splice(i, 1);

const submit = () => {
    form.benefits = form.benefits.filter((b) => b.trim());
    form.faq = form.faq.filter((f) => f.question.trim());
    form.put(route("app.offers.update", props.offer.id));
};

const togglePublish = () => {
    router.post(route("app.offers.publish", props.offer.id));
};

const copyLink = () => {
    const url = route("offer.show", { slug: props.offer.slug });
    navigator.clipboard.writeText(url);
    alert("Public link copied: " + url);
};

const deleteOffer = () => {
    if (confirm("Are you sure you want to delete this offer?")) {
        router.delete(route("app.offers.destroy", props.offer.id));
    }
};
</script>

<template>
    <Head :title="`Edit: ${offer.title}`" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link
                    href="/app/offers"
                    class="text-gray-500 hover:text-gray-300"
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
                <h1 class="text-xl font-bold text-white">Edit Offer</h1>
            </div>
        </template>

        <template #actions>
            <div class="flex items-center gap-2">
                <button
                    v-if="offer.is_published"
                    @click.prevent="copyLink"
                    class="p-2 text-gray-400 hover:text-cyan-400 hover:bg-white/[0.08] rounded-lg transition-colors border border-transparent hover:border-white/[0.05]"
                    title="Copy Public Link"
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
                <a
                    v-if="offer.is_published"
                    :href="route('offer.show', offer.slug)"
                    target="_blank"
                    class="p-2 text-gray-400 hover:text-cyan-400 hover:bg-white/[0.08] rounded-lg transition-colors border border-transparent hover:border-white/[0.05]"
                    title="View Public Page"
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
                </a>
                <button
                    @click="togglePublish"
                    :class="[
                        'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all',
                        offer.is_published
                            ? 'text-amber-400 border border-amber-500/30 hover:bg-amber-500/10'
                            : 'text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/10',
                    ]"
                >
                    {{ offer.is_published ? "Unpublish" : "Publish" }}
                </button>
            </div>
        </template>

        <div class="max-w-3xl space-y-6">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Status banner -->
                <div
                    v-if="offer.is_published"
                    class="flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20"
                >
                    <span class="w-2 h-2 rounded-full bg-emerald-400" />
                    <span class="text-sm text-emerald-400"
                        >Published {{ offer.published_at }}</span
                    >
                    <span class="text-xs text-gray-500 ml-auto"
                        >Public URL: /o/{{ offer.slug }}</span
                    >
                </div>

                <!-- Basic Info card -->
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
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Type</label
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
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            />
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-400 mb-1"
                            >Tagline</label
                        >
                        <input
                            v-model="form.tagline"
                            type="text"
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
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
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
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
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        />
                    </div>
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

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
                >
                    {{ form.processing ? "Saving..." : "Save Changes" }}
                </button>
            </form>

            <!-- Version History -->
            <div
                v-if="versions.length"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6"
            >
                <h2
                    class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-3"
                >
                    Version History
                </h2>
                <div class="space-y-2">
                    <div
                        v-for="v in versions"
                        :key="v.id"
                        class="flex items-center justify-between px-3 py-2 rounded-lg bg-white/[0.02] text-xs"
                    >
                        <span class="text-gray-300">v{{ v.version }}</span>
                        <span class="text-gray-500">{{ v.generated_by }}</span>
                        <span class="text-gray-500">{{ v.created_at }}</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div
                class="rounded-xl bg-red-500/5 border border-red-500/20 p-6 mt-8"
            >
                <h2
                    class="text-sm font-semibold text-red-500 uppercase tracking-wider mb-2"
                >
                    Danger Zone
                </h2>
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="text-sm text-white font-medium">
                            Delete Offer
                        </p>
                        <p class="text-xs text-gray-500">
                            Once deleted, it will be removed from your dashboard
                            and public pages.
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="deleteOffer"
                        class="px-4 py-2 text-sm font-medium text-red-400 bg-red-500/10 hover:bg-red-500/20 rounded-lg transition-colors border border-red-500/20"
                    >
                        Delete Offer
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
