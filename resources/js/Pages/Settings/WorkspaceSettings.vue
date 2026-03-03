<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    workspace: Object,
});

const form = useForm({
    name: props.workspace?.name || "",
    slug: props.workspace?.slug || "",
    brand_color: props.workspace?.brand_color || "#06b6d4",
    secondary_color: props.workspace?.secondary_color || "#8b5cf6",
    font_family: props.workspace?.font_family || "Inter",
    chat_widget_enabled: props.workspace?.chat_widget_enabled || false,
    chat_greeting: props.workspace?.chat_greeting || "Hi! How can I help you?",
    chat_email: props.workspace?.chat_email || "",
});

const presetColors = [
    "#06b6d4",
    "#8b5cf6",
    "#10b981",
    "#f59e0b",
    "#ef4444",
    "#ec4899",
    "#3b82f6",
    "#14b8a6",
    "#f97316",
    "#6366f1",
];

const fontOptions = [
    "Inter",
    "Poppins",
    "Roboto",
    "Outfit",
    "Space Grotesk",
    "DM Sans",
    "Plus Jakarta Sans",
    "Nunito",
];

const activeTab = ref("general");

const submit = () => {
    form.put(route("app.settings.update"));
};

const previewGradient = computed(() => {
    return `linear-gradient(135deg, ${form.brand_color}, ${form.secondary_color})`;
});
</script>

<template>
    <Head title="Workspace Settings" />

    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Workspace Settings</h1>
        </template>

        <div class="max-w-3xl">
            <!-- Tab Navigation -->
            <div
                class="flex gap-1 mb-6 bg-white/[0.04] rounded-xl p-1 border border-white/[0.06]"
            >
                <button
                    v-for="tab in [
                        { id: 'general', label: '⚙️ General' },
                        { id: 'theme', label: '🎨 Theme' },
                        { id: 'chat', label: '💬 Live Chat' },
                    ]"
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="[
                        'flex-1 px-4 py-2.5 text-sm font-medium rounded-lg transition-all',
                        activeTab === tab.id
                            ? 'bg-white/[0.08] text-white shadow-sm'
                            : 'text-gray-500 hover:text-gray-300',
                    ]"
                >
                    {{ tab.label }}
                </button>
            </div>

            <form @submit.prevent="submit">
                <!-- General Tab -->
                <div
                    v-show="activeTab === 'general'"
                    class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-5"
                >
                    <h2 class="text-lg font-semibold text-white">
                        General Settings
                    </h2>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-300 mb-1.5"
                            >Workspace Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                            placeholder="My Workspace"
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
                            class="block text-sm font-medium text-gray-300 mb-1.5"
                            >Workspace Slug</label
                        >
                        <input
                            v-model="form.slug"
                            type="text"
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                            placeholder="my-workspace"
                        />
                        <p
                            v-if="form.errors.slug"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.slug }}
                        </p>
                    </div>
                </div>

                <!-- Theme Tab -->
                <div v-show="activeTab === 'theme'" class="space-y-5">
                    <!-- Preview -->
                    <div
                        class="rounded-xl border border-white/[0.08] overflow-hidden"
                    >
                        <div
                            class="h-20 flex items-center justify-center"
                            :style="{ background: previewGradient }"
                        >
                            <span
                                class="text-white font-bold text-lg drop-shadow-lg"
                                :style="{ fontFamily: form.font_family }"
                            >
                                {{ form.name || "Your Brand" }}
                            </span>
                        </div>
                        <div class="bg-white/[0.04] p-4 text-center">
                            <p class="text-xs text-gray-500">
                                Live preview of your brand theme
                            </p>
                        </div>
                    </div>

                    <!-- Colors -->
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-5"
                    >
                        <h2 class="text-lg font-semibold text-white">
                            🎨 Brand Colors
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Primary Color -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-300 mb-2"
                                    >Primary Color</label
                                >
                                <div class="flex items-center gap-3 mb-2">
                                    <input
                                        v-model="form.brand_color"
                                        type="color"
                                        class="w-10 h-10 rounded-lg border border-white/[0.1] cursor-pointer bg-transparent"
                                    />
                                    <input
                                        v-model="form.brand_color"
                                        type="text"
                                        class="flex-1 px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                    />
                                </div>
                                <div class="flex gap-1.5 flex-wrap">
                                    <button
                                        v-for="c in presetColors"
                                        :key="'p-' + c"
                                        type="button"
                                        @click="form.brand_color = c"
                                        class="w-6 h-6 rounded-full border-2 transition-transform hover:scale-110"
                                        :class="
                                            form.brand_color === c
                                                ? 'border-white scale-110'
                                                : 'border-transparent'
                                        "
                                        :style="{ backgroundColor: c }"
                                    />
                                </div>
                            </div>

                            <!-- Secondary Color -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-300 mb-2"
                                    >Secondary Color</label
                                >
                                <div class="flex items-center gap-3 mb-2">
                                    <input
                                        v-model="form.secondary_color"
                                        type="color"
                                        class="w-10 h-10 rounded-lg border border-white/[0.1] cursor-pointer bg-transparent"
                                    />
                                    <input
                                        v-model="form.secondary_color"
                                        type="text"
                                        class="flex-1 px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                    />
                                </div>
                                <div class="flex gap-1.5 flex-wrap">
                                    <button
                                        v-for="c in presetColors"
                                        :key="'s-' + c"
                                        type="button"
                                        @click="form.secondary_color = c"
                                        class="w-6 h-6 rounded-full border-2 transition-transform hover:scale-110"
                                        :class="
                                            form.secondary_color === c
                                                ? 'border-white scale-110'
                                                : 'border-transparent'
                                        "
                                        :style="{ backgroundColor: c }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Font -->
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-4"
                    >
                        <h2 class="text-lg font-semibold text-white">
                            ✏️ Typography
                        </h2>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-300 mb-2"
                                >Font Family</label
                            >
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <button
                                    v-for="font in fontOptions"
                                    :key="font"
                                    type="button"
                                    @click="form.font_family = font"
                                    :class="[
                                        'px-3 py-2.5 rounded-lg text-sm transition-all text-center border',
                                        form.font_family === font
                                            ? 'bg-white/[0.08] text-white border-cyan-500/30'
                                            : 'bg-white/[0.02] text-gray-400 border-white/[0.06] hover:bg-white/[0.06]',
                                    ]"
                                    :style="{ fontFamily: font }"
                                >
                                    {{ font }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Tab -->
                <div v-show="activeTab === 'chat'" class="space-y-5">
                    <div
                        class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 space-y-5"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-white">
                                    💬 Live Chat Widget
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">
                                    Show a chat widget on your public offer
                                    pages so buyers can reach you.
                                </p>
                            </div>
                            <!-- Toggle -->
                            <button
                                type="button"
                                @click="
                                    form.chat_widget_enabled =
                                        !form.chat_widget_enabled
                                "
                                :class="[
                                    'relative w-12 h-6 rounded-full transition-colors duration-200',
                                    form.chat_widget_enabled
                                        ? 'bg-cyan-500'
                                        : 'bg-white/[0.12]',
                                ]"
                            >
                                <span
                                    :class="[
                                        'absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200',
                                        form.chat_widget_enabled
                                            ? 'translate-x-6'
                                            : 'translate-x-0.5',
                                    ]"
                                />
                            </button>
                        </div>

                        <template v-if="form.chat_widget_enabled">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-300 mb-1.5"
                                    >Greeting Message</label
                                >
                                <input
                                    v-model="form.chat_greeting"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                    placeholder="Hi! How can I help you?"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-300 mb-1.5"
                                    >Contact Email</label
                                >
                                <input
                                    v-model="form.chat_email"
                                    type="email"
                                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none"
                                    placeholder="support@yourbrand.com"
                                />
                                <p class="text-xs text-gray-600 mt-1">
                                    Chat messages will be forwarded to this
                                    email.
                                </p>
                            </div>

                            <!-- Preview -->
                            <div
                                class="rounded-xl border border-white/[0.08] bg-white/[0.02] p-4"
                            >
                                <p class="text-xs text-gray-500 mb-3">
                                    Widget Preview:
                                </p>
                                <div class="flex items-end justify-end">
                                    <div class="max-w-[220px]">
                                        <div
                                            class="rounded-2xl rounded-br-sm px-4 py-3 text-sm text-white"
                                            :style="{
                                                background: previewGradient,
                                            }"
                                        >
                                            {{
                                                form.chat_greeting ||
                                                "Hi! How can I help you?"
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-3">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl shadow-lg"
                                        :style="{ background: previewGradient }"
                                    >
                                        💬
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Submit -->
                <div class="mt-6 flex items-center gap-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Save Settings</span>
                    </button>
                    <transition
                        enter-active-class="transition duration-300"
                        enter-from-class="opacity-0 translate-y-1"
                        enter-to-class="opacity-100 translate-y-0"
                    >
                        <span
                            v-if="form.recentlySuccessful"
                            class="text-sm text-emerald-400"
                            >✓ Saved!</span
                        >
                    </transition>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
