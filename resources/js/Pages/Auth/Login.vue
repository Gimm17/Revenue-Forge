<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Sign In" />

    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl mb-1">Welcome back</h2>
    <p class="text-sm text-gray-500 mb-6">
        Sign in to your RevenueForge account
    </p>

    <div v-if="status" class="mb-4 text-sm font-medium text-emerald-400">
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
                placeholder="email@example.com"
            />
            <p v-if="form.errors.email" class="text-red-400 text-xs mt-1.5">
                {{ form.errors.email }}
            </p>
        </div>

        <div>
            <label
                for="password"
                class="block text-xs font-medium text-gray-500 mb-1.5"
                >Password</label
            >
            <input
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="current-password"
                class="w-full px-4 py-2.5 bg-white border border-gray-200 placeholder-gray-400 rounded-lg text-gray-900 text-sm placeholder-gray-600 focus:border-[#00D261]/50 focus:outline-none focus:ring-1 focus:ring-[#00D261]/30 transition-colors"
                placeholder="••••••••"
            />
            <p v-if="form.errors.password" class="text-red-400 text-xs mt-1.5">
                {{ form.errors.password }}
            </p>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input
                    type="checkbox"
                    v-model="form.remember"
                    class="rounded border-white/20 bg-gray-50 text-[#00D261] focus:ring-[#00D261]/30 focus:ring-offset-0"
                />
                <span class="text-sm text-gray-500">Remember me</span>
            </label>
            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-xs text-[#00D261] hover:text-[#00D261] transition-colors"
            >
                Forgot password?
            </Link>
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-lg hover:scale-105 transition-all shadow-lg shadow-[#25f47b]/30 disabled:opacity-50"
        >
            {{ form.processing ? "Signing in..." : "Sign In" }}
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <Link
            :href="route('register')"
            class="text-[#00D261] hover:text-[#00D261] font-medium transition-colors"
            >Create one</Link
        >
    </p>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
export default { layout: GuestLayout };
</script>
