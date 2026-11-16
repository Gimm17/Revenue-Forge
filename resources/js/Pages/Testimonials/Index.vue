<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    ChatBubbleLeftRightIcon,
    PlusIcon,
    XMarkIcon,
    TrashIcon,
    StarIcon as StarIconOutline,
} from "@heroicons/vue/24/outline";
import { StarIcon as StarIconSolid } from "@heroicons/vue/24/solid";

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
</script>

<template>
    <Head title="Testimonials" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl"
                    >
                        Testimonials
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage what your customers are saying about your offers.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        @click="showCreate = !showCreate"
                        :class="[
                            'inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white rounded-xl transition-all shadow-sm',
                            showCreate
                                ? 'bg-gray-900 hover:bg-gray-800'
                                : 'bg-[#00D261] hover:bg-[#009B48] hover:-translate-y-0.5 hover:shadow-md',
                        ]"
                    >
                        <XMarkIcon v-if="showCreate" class="w-5 h-5" />
                        <PlusIcon v-else class="w-5 h-5 -ml-1 text-white/90" />
                        <span v-if="!showCreate">Add Testimonial</span>
                        <span v-else>Cancel</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Create Form -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 -translate-y-4 scale-95"
        >
            <div
                v-if="showCreate"
                class="rounded-2xl bg-white shadow-sm border border-gray-100 p-8 mb-8 mt-6 relative overflow-hidden"
            >
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-[#00D261]/5 rounded-bl-[100px] pointer-events-none"
                ></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-6 text-gray-900">
                        <div
                            class="p-2 bg-[#00D261]/10 rounded-lg text-[#00D261]"
                        >
                            <ChatBubbleLeftRightIcon class="w-5 h-5" />
                        </div>
                        <h2 class="text-lg font-bold">
                            Create New Testimonial
                        </h2>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-1.5"
                                    >Customer Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm focus:border-[#00D261] focus:ring focus:ring-[#00D261]/20 focus:outline-none transition-shadow placeholder:text-gray-400 font-medium font-manrope"
                                    placeholder="John Doe"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-red-500 text-xs mt-1.5 font-medium"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-1.5"
                                    >Role / Title</label
                                >
                                <input
                                    v-model="form.role"
                                    type="text"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm focus:border-[#00D261] focus:ring focus:ring-[#00D261]/20 focus:outline-none transition-shadow placeholder:text-gray-400 font-medium font-manrope"
                                    placeholder="CEO at Company"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1.5"
                                >Testimonial Content</label
                            >
                            <textarea
                                v-model="form.content"
                                rows="4"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm focus:border-[#00D261] focus:ring focus:ring-[#00D261]/20 focus:outline-none transition-shadow placeholder:text-gray-400 resize-none font-medium font-manrope"
                                placeholder="This product completely changed my life... it's amazing!"
                            />
                            <p
                                v-if="form.errors.content"
                                class="text-red-500 text-xs mt-1.5 font-medium"
                            >
                                {{ form.errors.content }}
                            </p>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-12 gap-6 bg-gray-50/50 p-6 rounded-xl border border-gray-100"
                        >
                            <div class="sm:col-span-4">
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-2"
                                    >Rating</label
                                >
                                <div class="flex gap-1.5">
                                    <button
                                        v-for="n in 5"
                                        :key="n"
                                        type="button"
                                        @click="form.rating = n"
                                        class="transition-transform hover:scale-110 focus:outline-none"
                                    >
                                        <StarIconSolid
                                            v-if="n <= form.rating"
                                            class="w-8 h-8 text-yellow-400 drop-shadow-sm"
                                        />
                                        <StarIconOutline
                                            v-else
                                            class="w-8 h-8 text-gray-300"
                                        />
                                    </button>
                                </div>
                            </div>
                            <div class="sm:col-span-5">
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-2"
                                    >Associated Offer (Optional)</label
                                >
                                <div class="relative">
                                    <select
                                        v-model="form.offer_id"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 text-sm focus:border-[#00D261] focus:ring focus:ring-[#00D261]/20 focus:outline-none transition-shadow font-medium appearance-none cursor-pointer"
                                    >
                                        <option value="">
                                            General Testimonial (All Offers)
                                        </option>
                                        <option
                                            v-for="o in offers"
                                            :key="o.id"
                                            :value="o.id"
                                        >
                                            {{ o.title }}
                                        </option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="sm:col-span-3 flex items-center pt-2 sm:pt-6"
                            >
                                <label
                                    class="flex items-center gap-3 cursor-pointer group"
                                >
                                    <div class="relative flex items-center">
                                        <input
                                            v-model="form.is_featured"
                                            type="checkbox"
                                            class="peer sr-only"
                                        />
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#00D261]/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00D261]"
                                        ></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-gray-900 group-hover:text-[#00D261] transition-colors"
                                            >Featured</span
                                        >
                                        <span
                                            class="text-[10px] text-gray-500 font-medium"
                                            >Show on top</span
                                        >
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-extrabold text-[#009B48] bg-[#00D261]/10 rounded-xl hover:bg-[#00D261]/20 transition-all shadow-sm ring-1 ring-inset ring-[#00D261]/20 disabled:opacity-50"
                            >
                                <span
                                    v-if="form.processing"
                                    class="w-4 h-4 border-2 border-[#009B48] border-t-transparent rounded-full animate-spin"
                                ></span>
                                {{
                                    form.processing
                                        ? "Saving..."
                                        : "Save Testimonial"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Testimonials Grid -->
        <div
            v-if="testimonials && testimonials.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6"
        >
            <div
                v-for="t in testimonials"
                :key="t.id"
                class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6 relative group flex flex-col h-full hover:shadow-md hover:border-gray-200 transition-all"
            >
                <div
                    class="flex justify-between items-start mb-4 relative z-10 w-full"
                >
                    <!-- Rating -->
                    <div class="flex gap-0.5">
                        <StarIconSolid
                            v-for="n in t.rating"
                            :key="`s-${n}`"
                            class="w-5 h-5 text-yellow-400"
                        />
                        <StarIconOutline
                            v-for="n in 5 - t.rating"
                            :key="`e-${n}`"
                            class="w-5 h-5 text-gray-200"
                        />
                    </div>

                    <div class="flex items-center gap-1">
                        <!-- Featured Badge -->
                        <span
                            v-if="t.is_featured"
                            class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-600/20 uppercase tracking-wider transition-opacity"
                            :class="{
                                'opacity-0 group-hover:opacity-100 absolute right-16': true,
                            }"
                        >
                            <StarIconSolid class="w-3 h-3" /> Featured
                        </span>

                        <!-- Actions (Hover) -->
                        <div
                            class="flex gap-1 bg-white/90 backdrop-blur-sm rounded-lg p-1 opacity-0 group-hover:opacity-100 transition-opacity absolute right-4 top-4 z-20 shadow-sm border border-gray-100"
                        >
                            <button
                                @click="toggleFeatured(t.id)"
                                :class="[
                                    'p-1.5 rounded-md hover:bg-gray-100 transition-colors',
                                    t.is_featured
                                        ? 'text-amber-500'
                                        : 'text-gray-400 hover:text-amber-500',
                                ]"
                                :title="t.is_featured ? 'Unfeature' : 'Feature'"
                            >
                                <StarIconSolid class="w-4 h-4" />
                            </button>
                            <button
                                @click="deleteTestimonial(t.id)"
                                class="p-1.5 rounded-md text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                title="Delete Testimonial"
                            >
                                <TrashIcon class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative mb-6 flex-grow">
                    <span
                        class="absolute -top-4 -left-2 text-6xl text-gray-100 font-serif leading-none select-none"
                        >"</span
                    >
                    <p
                        class="text-sm font-medium text-gray-700 leading-relaxed font-manrope relative z-10 p-2"
                    >
                        {{ t.content }}
                    </p>
                </div>

                <!-- Author details at bottom -->
                <div
                    class="flex items-center justify-between pt-4 border-t border-gray-50 mt-auto"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-[#25f47b] to-[#00D261] flex items-center justify-center text-white font-bold text-lg shadow-sm"
                        >
                            {{ t.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <p
                                class="text-sm font-bold text-gray-900 leading-none mb-1 shadow-sm"
                            >
                                {{ t.name }}
                            </p>
                            <p
                                class="text-xs font-medium text-gray-500 truncate max-w-[150px]"
                            >
                                {{ t.role || t.offer || "Customer" }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else-if="!showCreate"
            class="rounded-2xl bg-white shadow-sm border border-gray-100 p-16 text-center mt-6 flex flex-col items-center justify-center min-h-[400px]"
        >
            <div
                class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6"
            >
                <ChatBubbleLeftRightIcon class="w-10 h-10 text-gray-300" />
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">
                No Testimonials Yet
            </h3>
            <p class="text-sm font-medium text-gray-500 mb-8 max-w-sm mx-auto">
                Social proof is powerful. Add your first customer testimonial to
                start building trust on your offer pages.
            </p>
            <button
                @click="showCreate = true"
                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-[#00D261] rounded-xl hover:bg-[#009B48] hover:-translate-y-0.5 transition-all shadow-lg hover:shadow-xl shadow-[#00D261]/20"
            >
                <PlusIcon class="w-5 h-5 -ml-1 text-white/90" />
                Add First Testimonial
            </button>
        </div>
    </AuthenticatedLayout>
</template>
