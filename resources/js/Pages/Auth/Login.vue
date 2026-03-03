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

    <h2 class="text-xl font-bold text-white mb-1">Welcome back</h2>
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
                class="block text-xs font-medium text-gray-400 mb-1.5"
                >Email</label
            >
            <input
                id="email"
                type="email"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-600 focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors"
                placeholder="email@example.com"
            />
            <p v-if="form.errors.email" class="text-red-400 text-xs mt-1.5">
                {{ form.errors.email }}
            </p>
        </div>

        <div>
            <label
                for="password"
                class="block text-xs font-medium text-gray-400 mb-1.5"
                >Password</label
            >
            <input
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="current-password"
                class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-600 focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors"
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
                    class="rounded border-white/20 bg-white/[0.06] text-cyan-500 focus:ring-cyan-500/30 focus:ring-offset-0"
                />
                <span class="text-sm text-gray-400">Remember me</span>
            </label>
            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors"
            >
                Forgot password?
            </Link>
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
        >
            {{ form.processing ? "Signing in..." : "Sign In" }}
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <Link
            :href="route('register')"
            class="text-cyan-400 hover:text-cyan-300 font-medium transition-colors"
            >Create one</Link
        >
    </p>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
export default { layout: GuestLayout };
</script>
