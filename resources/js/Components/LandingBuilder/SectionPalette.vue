<script setup>
/**
 * SectionPalette — sidebar showing available section types.
 * Each card is draggable and emits an 'add' event on click.
 */
const emit = defineEmits(["add"]);

const sectionTypes = [
    { type: "hero", label: "Hero", icon: "🌟", desc: "Big headline + CTA" },
    { type: "text", label: "Text", icon: "📝", desc: "Paragraph block" },
    {
        type: "benefits",
        label: "Benefits",
        icon: "✅",
        desc: "Feature list",
    },
    {
        type: "pricing",
        label: "Pricing",
        icon: "💰",
        desc: "Price card",
    },
    { type: "faq", label: "FAQ", icon: "❓", desc: "Q&A accordion" },
    {
        type: "testimonial",
        label: "Testimonial",
        icon: "💬",
        desc: "Social proof",
    },
    { type: "cta", label: "CTA", icon: "🚀", desc: "Action banner" },
    { type: "image", label: "Image", icon: "🖼️", desc: "Full-width image" },
    { type: "video", label: "Video", icon: "🎬", desc: "YouTube/Vimeo" },
    {
        type: "divider",
        label: "Divider",
        icon: "➖",
        desc: "Visual separator",
    },
];

const defaultContent = {
    hero: {
        headline: "Your Amazing Headline",
        subtitle: "A short description here",
        cta_text: "Buy Now",
        cta_color: "cyan",
    },
    text: { body: "Write your content here..." },
    benefits: {
        items: [
            { icon: "✓", text: "First benefit" },
            { icon: "✓", text: "Second benefit" },
            { icon: "✓", text: "Third benefit" },
        ],
    },
    pricing: { price: 99000, currency: "IDR", interval: null, label: "Plan" },
    faq: {
        items: [
            {
                question: "What is this?",
                answer: "Your answer here...",
            },
        ],
    },
    testimonial: {
        items: [
            {
                name: "John Doe",
                role: "CEO",
                quote: "This product is amazing!",
            },
        ],
    },
    cta: {
        headline: "Ready to get started?",
        button_text: "Get Started",
        button_color: "violet",
    },
    image: { url: "", alt: "", caption: "" },
    video: { url: "" },
    divider: { style: "line" },
};

const onDragStart = (e, type) => {
    e.dataTransfer.setData("section-type", type);
    e.dataTransfer.effectAllowed = "copy";
};

const addSection = (type) => {
    emit("add", {
        id: "sec_" + Date.now() + "_" + Math.random().toString(36).slice(2, 6),
        type,
        order: 0,
        content: JSON.parse(JSON.stringify(defaultContent[type])),
    });
};
</script>

<template>
    <div class="space-y-2">
        <h3
            class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-1 mb-3"
        >
            Add Sections
        </h3>
        <button
            v-for="s in sectionTypes"
            :key="s.type"
            @click="addSection(s.type)"
            draggable="true"
            @dragstart="onDragStart($event, s.type)"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white/[0.04] border border-white/[0.06] hover:bg-white/[0.08] hover:border-cyan-500/30 transition-all cursor-grab active:cursor-grabbing text-left group"
        >
            <span class="text-lg">{{ s.icon }}</span>
            <div>
                <p
                    class="text-sm text-white font-medium group-hover:text-cyan-400 transition-colors"
                >
                    {{ s.label }}
                </p>
                <p class="text-[10px] text-gray-500">{{ s.desc }}</p>
            </div>
        </button>
    </div>
</template>
