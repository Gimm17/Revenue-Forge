<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    testimonials: Array,
    offers: Array,
});

const showCreate = ref(false);

const form = useForm({
    name: "",
    role: "",
    content: "",
    rating: 5,
    offer_id: "",
    is_featured: false,
});

const submit = () => {
    form.post(route("app.testimonials.store"), {
        onSuccess: () => {
            showCreate.value = false;
            form.reset();
            form.rating = 5;
        },
    });
};

const deleteTestimonial = (id) => {
    if (confirm("Delete this testimonial?")) {
        router.delete(route("app.testimonials.destroy", id));
    }
};

const toggleFeatured = (id) => {
    router.post(route("app.testimonials.toggle", id));
};

const stars = (n) => "⭐".repeat(n);
</script>

<template>
    <Head title="Testimonials" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Testimonials</h1>
        </template>

        <template #actions>
            <button
                @click="showCreate = !showCreate"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
            >
                <span v-if="!showCreate">+ Add Testimonial</span>
                <span v-else>✕ Cancel</span>
            </button>
        </template>

        <!-- Create Form -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="showCreate"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 mb-6"
            >
                <h2 class="text-sm font-semibold text-white mb-4">
                    Add Testimonial
                </h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Customer Name</label
                            >
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                placeholder="John Doe"
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
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Role / Title</label
                            >
                            <input
                                v-model="form.role"
                                type="text"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                                placeholder="CEO at Company"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-400 mb-1"
                            >Testimonial</label
                        >
                        <textarea
                            v-model="form.content"
                            rows="3"
                            class="w-full px-4 py-3 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
                            placeholder="This product changed my life..."
                        />
                        <p
                            v-if="form.errors.content"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.content }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Rating</label
                            >
                            <div class="flex gap-1">
                                <button
                                    v-for="n in 5"
                                    :key="n"
                                    type="button"
                                    @click="form.rating = n"
                                    class="text-2xl transition-transform hover:scale-110"
                                    :class="
                                        n <= form.rating ? '' : 'opacity-30'
                                    "
                                >
                                    ⭐
                                </button>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-400 mb-1"
                                >Offer</label
                            >
                            <select
                                v-model="form.offer_id"
                                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            >
                                <option value="">All Offers</option>
                                <option
                                    v-for="o in offers"
                                    :key="o.id"
                                    :value="o.id"
                                >
                                    {{ o.title }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-end pb-1">
                            <label
                                class="flex items-center gap-2 cursor-pointer"
                            >
                                <input
                                    v-model="form.is_featured"
                                    type="checkbox"
                                    class="w-4 h-4 rounded border-white/[0.2] bg-white/[0.06] text-cyan-500 focus:ring-cyan-500/30"
                                />
                                <span class="text-sm text-gray-300"
                                    >Featured</span
                                >
                            </label>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
                    >
                        {{ form.processing ? "Adding..." : "Add Testimonial" }}
                    </button>
                </form>
            </div>
        </transition>

        <!-- Testimonials Grid -->
        <div
            v-if="testimonials && testimonials.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 gap-4"
        >
            <div
                v-for="t in testimonials"
                :key="t.id"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-5 relative group hover:border-white/[0.12] transition-colors"
            >
                <!-- Featured badge -->
                <span
                    v-if="t.is_featured"
                    class="absolute top-3 right-3 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400"
                    >⭐ Featured</span
                >

                <!-- Quote -->
                <p class="text-sm text-gray-300 leading-relaxed mb-3 italic">
                    "{{ t.content }}"
                </p>

                <!-- Rating -->
                <div class="text-xs mb-3">{{ stars(t.rating) }}</div>

                <!-- Author -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-white">
                            {{ t.name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ t.role || t.offer }}
                        </p>
                    </div>
                    <div
                        class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <button
                            @click="toggleFeatured(t.id)"
                            class="p-1 text-gray-500 hover:text-amber-400 rounded"
                            :title="t.is_featured ? 'Unfeature' : 'Feature'"
                        >
                            {{ t.is_featured ? "⭐" : "☆" }}
                        </button>
                        <button
                            @click="deleteTestimonial(t.id)"
                            class="p-1 text-gray-500 hover:text-red-400 rounded"
                        >
                            🗑️
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else-if="!showCreate"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-12 text-center"
        >
            <div class="text-5xl mb-4">💬</div>
            <h3 class="text-lg font-semibold text-white mb-2">
                No Testimonials Yet
            </h3>
            <p class="text-sm text-gray-500 mb-4">
                Add customer testimonials to build trust on your offer pages.
            </p>
            <button
                @click="showCreate = true"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg"
            >
                + Add First Testimonial
            </button>
        </div>
    </AppLayout>
</template>
