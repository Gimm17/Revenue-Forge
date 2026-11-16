<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl mb-2">Forgot Password</h2>
        <div class="mb-6 text-sm text-gray-500 leading-relaxed">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-6 text-sm font-medium text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 p-3 rounded-lg"
        >
            {{ status }}
        </div>

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

            <div class="pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-lg hover:scale-105 transition-all shadow-lg shadow-[#25f47b]/30 disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? "Sending link..."
                            : "Email Password Reset Link"
                    }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
