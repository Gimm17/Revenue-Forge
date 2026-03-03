<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    workspace: Object,
});

const form = useForm({
    name: props.workspace?.name || "",
    slug: props.workspace?.slug || "",
    brand_color: props.workspace?.brand_color || "#06b6d4",
});

const submit = () => {
    form.put(route("app.settings.update"));
};
</script>

<template>
    <Head title="Workspace Settings" />

    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Workspace Settings</h1>
        </template>

        <div class="max-w-2xl">
            <div
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6"
            >
                <h2 class="text-lg font-semibold text-white mb-6">
                    General Settings
                </h2>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Workspace Name -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-300 mb-1.5"
                            >Workspace Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-500 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none transition-colors"
                            placeholder="My Workspace"
                        />
                        <p
                            v-if="form.errors.name"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-300 mb-1.5"
                            >Workspace Slug</label
                        >
                        <input
                            v-model="form.slug"
                            type="text"
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-500 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none transition-colors"
                            placeholder="my-workspace"
                        />
                        <p
                            v-if="form.errors.slug"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.slug }}
                        </p>
                    </div>

                    <!-- Brand Color -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-300 mb-1.5"
                            >Brand Color</label
                        >
                        <div class="flex items-center gap-3">
                            <input
                                v-model="form.brand_color"
                                type="color"
                                class="w-10 h-10 rounded-lg border border-white/[0.1] cursor-pointer bg-transparent"
                            />
                            <input
                                v-model="form.brand_color"
                                type="text"
                                class="flex-1 px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-500 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 focus:outline-none transition-colors"
                                placeholder="#06b6d4"
                            />
                        </div>
                        <p
                            v-if="form.errors.brand_color"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.brand_color }}
                        </p>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Saving...</span>
                            <span v-else>Save Settings</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
