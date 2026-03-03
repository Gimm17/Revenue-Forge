<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import SectionPalette from "@/Components/LandingBuilder/SectionPalette.vue";
import SectionRenderer from "@/Components/LandingBuilder/SectionRenderer.vue";
import SectionEditor from "@/Components/LandingBuilder/SectionEditor.vue";
import DragHandle from "@/Components/LandingBuilder/DragHandle.vue";

const props = defineProps({
    offer: Object,
    landingPage: Object,
});

// Reactive sections array
const sections = ref(
    JSON.parse(JSON.stringify(props.landingPage.sections || [])),
);

// Editing state
const editingSection = ref(null);
const showEditor = ref(false);

// Drag state
const dragIndex = ref(null);
const dropIndex = ref(null);

// Form for saving
const form = useForm({
    sections: [],
    theme: props.landingPage.theme || "dark",
    custom_css: props.landingPage.custom_css || "",
    meta_title: props.landingPage.meta_title || "",
    meta_description: props.landingPage.meta_description || "",
});

const saveSuccess = ref(false);

// Sort sections by order
const sortedSections = computed(() =>
    [...sections.value].sort((a, b) => a.order - b.order),
);

// === CRUD Operations ===
const addSection = (newSection) => {
    newSection.order = sections.value.length;
    sections.value.push(newSection);
};

const updateSection = (updated) => {
    const idx = sections.value.findIndex((s) => s.id === updated.id);
    if (idx !== -1) sections.value[idx] = updated;
};

const deleteSection = (id) => {
    sections.value = sections.value.filter((s) => s.id !== id);
    // Reindex orders
    sections.value.forEach((s, i) => (s.order = i));
};

const editSection = (section) => {
    editingSection.value = section;
    showEditor.value = true;
};

// === Drag & Drop ===
const onDragStart = (e, index) => {
    dragIndex.value = index;
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text/plain", index);
};

const onDragOver = (e, index) => {
    e.preventDefault();
    e.dataTransfer.dropEffect = "move";
    dropIndex.value = index;
};

const onDragLeave = () => {
    dropIndex.value = null;
};

const onDrop = (e, targetIndex) => {
    e.preventDefault();

    // Check if it's a new section from palette
    const sectionType = e.dataTransfer.getData("section-type");
    if (sectionType) {
        // Insert new section at target position
        const palette = document.querySelector("[data-palette]");
        if (palette) {
            // We'll handle this through the addSection with order
            // For now, skip palette drag — use click to add instead
        }
    }

    // Reorder existing section
    if (dragIndex.value !== null && dragIndex.value !== targetIndex) {
        const sorted = sortedSections.value;
        const [moved] = sorted.splice(dragIndex.value, 1);
        sorted.splice(targetIndex, 0, moved);
        sorted.forEach((s, i) => (s.order = i));
        sections.value = [...sorted];
    }

    dragIndex.value = null;
    dropIndex.value = null;
};

const onDragEnd = () => {
    dragIndex.value = null;
    dropIndex.value = null;
};

// === Save ===
const save = () => {
    form.sections = sections.value.map((s, i) => ({ ...s, order: i }));
    form.put(route("app.offers.landing-builder.update", props.offer.id), {
        preserveScroll: true,
        onSuccess: () => {
            saveSuccess.value = true;
            setTimeout(() => (saveSuccess.value = false), 3000);
        },
    });
};

// === SEO toggle ===
const showSeo = ref(false);
</script>

<template>
    <Head :title="`Landing Builder: ${offer.title}`" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('app.offers.edit', offer.id)"
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
                <h1 class="text-xl font-bold text-white">
                    🎨 Landing Page Builder
                </h1>
                <span class="text-xs text-gray-500">{{ offer.title }}</span>
            </div>
        </template>

        <template #actions>
            <div class="flex items-center gap-2">
                <a
                    v-if="offer.is_published"
                    :href="route('offer.show', offer.slug)"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-400 hover:text-cyan-400 border border-white/[0.06] rounded-lg hover:bg-white/[0.04] transition-all"
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
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                        />
                    </svg>
                    Preview
                </a>
                <button
                    @click="save"
                    :disabled="form.processing"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
                >
                    <svg
                        v-if="!form.processing && !saveSuccess"
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"
                        />
                    </svg>
                    <svg
                        v-else-if="saveSuccess"
                        class="w-4 h-4 text-emerald-400"
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
                    {{
                        form.processing
                            ? "Saving..."
                            : saveSuccess
                              ? "Saved!"
                              : "Save Page"
                    }}
                </button>
            </div>
        </template>

        <!-- Main Layout: Sidebar + Canvas -->
        <div class="flex gap-6 min-h-[calc(100vh-120px)]">
            <!-- Left Sidebar: Palette -->
            <div
                class="w-56 shrink-0 space-y-6 overflow-y-auto max-h-[calc(100vh-120px)] pr-2"
                data-palette
            >
                <SectionPalette @add="addSection" />

                <!-- SEO Settings -->
                <div>
                    <button
                        @click="showSeo = !showSeo"
                        class="w-full flex items-center justify-between px-1 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                    >
                        SEO Settings
                        <svg
                            class="w-3 h-3 transition-transform"
                            :class="{ 'rotate-180': showSeo }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                    <div v-if="showSeo" class="space-y-3 mt-2">
                        <div>
                            <label class="block text-[10px] text-gray-500 mb-1"
                                >Meta Title</label
                            >
                            <input
                                v-model="form.meta_title"
                                class="w-full px-2 py-1.5 bg-white/[0.06] border border-white/[0.1] rounded text-white text-xs focus:border-cyan-500/50 focus:outline-none"
                            />
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-500 mb-1"
                                >Meta Description</label
                            >
                            <textarea
                                v-model="form.meta_description"
                                rows="3"
                                class="w-full px-2 py-1.5 bg-white/[0.06] border border-white/[0.1] rounded text-white text-xs focus:border-cyan-500/50 focus:outline-none resize-none"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: Canvas -->
            <div class="flex-1 min-w-0">
                <!-- Empty state -->
                <div
                    v-if="!sections.length"
                    class="flex flex-col items-center justify-center py-24 text-center"
                >
                    <div class="text-5xl mb-4">🎨</div>
                    <h3 class="text-lg font-semibold text-white mb-2">
                        Start Building Your Page
                    </h3>
                    <p class="text-sm text-gray-500 max-w-md">
                        Click a section type from the left sidebar to add it to
                        your page. Drag sections to reorder them.
                    </p>
                </div>

                <!-- Sections list -->
                <div v-else class="space-y-1 pb-20">
                    <div
                        v-for="(section, index) in sortedSections"
                        :key="section.id"
                        class="relative group rounded-xl border-2 transition-all"
                        :class="[
                            dropIndex === index
                                ? 'border-cyan-500/50 bg-cyan-500/5'
                                : 'border-transparent hover:border-white/[0.1]',
                            dragIndex === index ? 'opacity-40' : '',
                        ]"
                        draggable="true"
                        @dragstart="onDragStart($event, index)"
                        @dragover="onDragOver($event, index)"
                        @dragleave="onDragLeave"
                        @drop="onDrop($event, index)"
                        @dragend="onDragEnd"
                    >
                        <DragHandle
                            :section="section"
                            @edit="editSection(section)"
                            @delete="deleteSection(section.id)"
                        />
                        <div class="pointer-events-none">
                            <SectionRenderer
                                :section="section"
                                :offer="offer"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Editor Modal -->
        <SectionEditor
            v-if="showEditor && editingSection"
            :section="editingSection"
            @update="updateSection"
            @close="showEditor = false"
        />
    </AppLayout>
</template>
