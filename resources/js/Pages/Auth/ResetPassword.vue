<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl mb-6">Reset Password</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label
                    for="email"
                    class="block text-xs font-medium text-gray-500 mb-1.5"
                    >Email</label
                >
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm placeholder-gray-600 focus:border-[#00D261]/50 focus:outline-none focus:ring-1 focus:ring-[#00D261]/30 transition-colors"
                />
                <p v-if="form.errors.email" class="text-red-400 text-xs mt-1.5">
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label
                    for="password"
                    class="block text-xs font-medium text-gray-500 mb-1.5"
                    >New Password</label
                >
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm placeholder-gray-600 focus:border-[#00D261]/50 focus:outline-none focus:ring-1 focus:ring-[#00D261]/30 transition-colors"
                    placeholder="••••••••"
                />
                <p
                    v-if="form.errors.password"
                    class="text-red-400 text-xs mt-1.5"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <div>
                <label
                    for="password_confirmation"
                    class="block text-xs font-medium text-gray-500 mb-1.5"
                    >Confirm Password</label
                >
                <input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm placeholder-gray-600 focus:border-[#00D261]/50 focus:outline-none focus:ring-1 focus:ring-[#00D261]/30 transition-colors"
                    placeholder="••••••••"
                />
                <p
                    v-if="form.errors.password_confirmation"
                    class="text-red-400 text-xs mt-1.5"
                >
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-lg hover:scale-105 transition-all shadow-lg shadow-[#25f47b]/30 disabled:opacity-50"
                >
                    {{ form.processing ? "Resetting..." : "Reset Password" }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
