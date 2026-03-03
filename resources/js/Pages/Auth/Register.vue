<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create Account" />

        <h2 class="text-xl font-bold text-white mb-1">Create your account</h2>
        <p class="text-sm text-gray-500 mb-6">
            Start building your revenue engine today
        </p>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label
                    for="name"
                    class="block text-xs font-medium text-gray-400 mb-1.5"
                    >Name</label
                >
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-600 focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors"
                    placeholder="Your full name"
                />
                <p v-if="form.errors.name" class="text-red-400 text-xs mt-1.5">
                    {{ form.errors.name }}
                </p>
            </div>

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
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-600 focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors"
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
                    class="block text-xs font-medium text-gray-400 mb-1.5"
                    >Confirm Password</label
                >
                <input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm placeholder-gray-600 focus:border-cyan-500/50 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-colors"
                    placeholder="••••••••"
                />
                <p
                    v-if="form.errors.password_confirmation"
                    class="text-red-400 text-xs mt-1.5"
                >
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
            >
                {{ form.processing ? "Creating account..." : "Create Account" }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <Link
                :href="route('login')"
                class="text-cyan-400 hover:text-cyan-300 font-medium transition-colors"
                >Sign in</Link
            >
        </p>
    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
export default { layout: GuestLayout };
</script>
