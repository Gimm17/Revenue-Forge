<script setup>
/**
 * DragHandle — overlay controls for each section in builder mode.
 * Shows drag handle, section type label, edit button, and delete button.
 */
defineProps({
    section: { type: Object, required: true },
});

const emit = defineEmits(["edit", "delete"]);

const typeIcons = {
    hero: "🌟",
    text: "📝",
    benefits: "✅",
    pricing: "💰",
    faq: "❓",
    testimonial: "💬",
    cta: "🚀",
    image: "🖼️",
    video: "🎬",
    divider: "➖",
};
</script>

<template>
    <div
        class="absolute top-2 left-2 right-2 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity z-10"
    >
        <!-- Drag Handle + Label -->
        <div
            class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-gray-900/90 border border-white/10 shadow-lg cursor-grab active:cursor-grabbing"
        >
            <svg
                class="w-3.5 h-3.5 text-gray-400"
                fill="currentColor"
                viewBox="0 0 16 16"
            >
                <circle cx="5" cy="3" r="1.5" />
                <circle cx="11" cy="3" r="1.5" />
                <circle cx="5" cy="8" r="1.5" />
                <circle cx="11" cy="8" r="1.5" />
                <circle cx="5" cy="13" r="1.5" />
                <circle cx="11" cy="13" r="1.5" />
            </svg>
            <span class="text-xs">{{ typeIcons[section.type] }}</span>
            <span class="text-[10px] text-gray-400 uppercase font-medium">{{
                section.type
            }}</span>
        </div>

        <!-- Actions -->
        <div
            class="flex items-center gap-1 px-1 py-1 rounded-lg bg-gray-900/90 border border-white/10 shadow-lg"
        >
            <button
                @click.stop="emit('edit')"
                class="p-1.5 text-gray-400 hover:text-cyan-400 hover:bg-white/10 rounded transition-colors"
                title="Edit Section"
            >
                <svg
                    class="w-3.5 h-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                </svg>
            </button>
            <button
                @click.stop="emit('delete')"
                class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-white/10 rounded transition-colors"
                title="Delete Section"
            >
                <svg
                    class="w-3.5 h-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
