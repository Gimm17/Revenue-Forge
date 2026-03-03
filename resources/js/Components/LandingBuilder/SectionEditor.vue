<script setup>
/**
 * SectionEditor — inline editing form for a section's content.
 * Renders different form fields based on section type.
 */
import { ref, watch } from "vue";

const props = defineProps({
    section: { type: Object, required: true },
});

const emit = defineEmits(["update", "close"]);

// Deep clone the content so we edit a copy
const content = ref(JSON.parse(JSON.stringify(props.section.content)));

watch(
    () => props.section,
    (s) => {
        content.value = JSON.parse(JSON.stringify(s.content));
    },
);

const save = () => {
    emit("update", { ...props.section, content: content.value });
    emit("close");
};

// Helpers for list items
const addBenefitItem = () => content.value.items.push({ icon: "✓", text: "" });
const removeBenefitItem = (i) => content.value.items.splice(i, 1);
const addFaqItem = () => content.value.items.push({ question: "", answer: "" });
const removeFaqItem = (i) => content.value.items.splice(i, 1);
const addTestimonialItem = () =>
    content.value.items.push({ name: "", role: "", quote: "" });
const removeTestimonialItem = (i) => content.value.items.splice(i, 1);
</script>

<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.self="emit('close')"
    >
        <div
            class="absolute inset-0 bg-black/70 backdrop-blur-sm"
            @click="emit('close')"
        />
        <div
            class="relative w-full max-w-lg max-h-[80vh] overflow-y-auto rounded-2xl bg-gray-900 border border-white/10 p-6 shadow-2xl"
        >
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-white capitalize">
                    Edit {{ section.type }} Section
                </h3>
                <button
                    @click="emit('close')"
                    class="text-gray-500 hover:text-white"
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
            </div>

            <!-- HERO -->
            <div v-if="section.type === 'hero'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Headline</label
                    >
                    <input
                        v-model="content.headline"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Subtitle</label
                    >
                    <input
                        v-model="content.subtitle"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >CTA Text</label
                    >
                    <input
                        v-model="content.cta_text"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
            </div>

            <!-- TEXT -->
            <div v-else-if="section.type === 'text'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Body Text</label
                    >
                    <textarea
                        v-model="content.body"
                        rows="6"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
                    />
                </div>
            </div>

            <!-- BENEFITS -->
            <div v-else-if="section.type === 'benefits'" class="space-y-3">
                <div
                    v-for="(item, i) in content.items"
                    :key="i"
                    class="flex gap-2"
                >
                    <input
                        v-model="item.icon"
                        class="w-12 px-2 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm text-center focus:border-cyan-500/50 focus:outline-none"
                    />
                    <input
                        v-model="item.text"
                        class="flex-1 px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="Benefit text"
                    />
                    <button
                        @click="removeBenefitItem(i)"
                        class="px-2 text-gray-500 hover:text-red-400"
                        v-if="content.items.length > 1"
                    >
                        ✕
                    </button>
                </div>
                <button
                    @click="addBenefitItem"
                    class="text-xs text-cyan-400 hover:text-cyan-300"
                >
                    + Add Benefit
                </button>
            </div>

            <!-- PRICING -->
            <div v-else-if="section.type === 'pricing'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Label</label
                    >
                    <input
                        v-model="content.label"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1"
                            >Price</label
                        >
                        <input
                            v-model.number="content.price"
                            type="number"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1"
                            >Interval</label
                        >
                        <select
                            v-model="content.interval"
                            class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        >
                            <option :value="null">One-time</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div v-else-if="section.type === 'faq'" class="space-y-3">
                <div
                    v-for="(item, i) in content.items"
                    :key="i"
                    class="space-y-2 p-3 bg-white/[0.02] rounded-lg border border-white/[0.04]"
                >
                    <input
                        v-model="item.question"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="Question"
                    />
                    <textarea
                        v-model="item.answer"
                        rows="2"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
                        placeholder="Answer"
                    />
                    <button
                        @click="removeFaqItem(i)"
                        class="text-xs text-gray-500 hover:text-red-400"
                        v-if="content.items.length > 1"
                    >
                        Remove
                    </button>
                </div>
                <button
                    @click="addFaqItem"
                    class="text-xs text-cyan-400 hover:text-cyan-300"
                >
                    + Add FAQ
                </button>
            </div>

            <!-- TESTIMONIAL -->
            <div v-else-if="section.type === 'testimonial'" class="space-y-3">
                <div
                    v-for="(item, i) in content.items"
                    :key="i"
                    class="space-y-2 p-3 bg-white/[0.02] rounded-lg border border-white/[0.04]"
                >
                    <input
                        v-model="item.name"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="Name"
                    />
                    <input
                        v-model="item.role"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="Role / Title"
                    />
                    <textarea
                        v-model="item.quote"
                        rows="2"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
                        placeholder="Testimonial quote"
                    />
                    <button
                        @click="removeTestimonialItem(i)"
                        class="text-xs text-gray-500 hover:text-red-400"
                        v-if="content.items.length > 1"
                    >
                        Remove
                    </button>
                </div>
                <button
                    @click="addTestimonialItem"
                    class="text-xs text-cyan-400 hover:text-cyan-300"
                >
                    + Add Testimonial
                </button>
            </div>

            <!-- CTA -->
            <div v-else-if="section.type === 'cta'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Headline</label
                    >
                    <input
                        v-model="content.headline"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Button Text</label
                    >
                    <input
                        v-model="content.button_text"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
            </div>

            <!-- IMAGE -->
            <div v-else-if="section.type === 'image'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Image URL</label
                    >
                    <input
                        v-model="content.url"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="https://..."
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Alt Text</label
                    >
                    <input
                        v-model="content.alt"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Caption</label
                    >
                    <input
                        v-model="content.caption"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    />
                </div>
            </div>

            <!-- VIDEO -->
            <div v-else-if="section.type === 'video'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >YouTube / Vimeo URL</label
                    >
                    <input
                        v-model="content.url"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                        placeholder="https://www.youtube.com/watch?v=..."
                    />
                </div>
            </div>

            <!-- DIVIDER -->
            <div v-else-if="section.type === 'divider'" class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1"
                        >Style</label
                    >
                    <select
                        v-model="content.style"
                        class="w-full px-3 py-2 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                    >
                        <option value="line">Line</option>
                        <option value="space">Space</option>
                        <option value="dots">Dots</option>
                    </select>
                </div>
            </div>

            <!-- Save Button -->
            <button
                @click="save"
                class="w-full mt-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all"
            >
                Save Changes
            </button>
        </div>
    </div>
</template>
