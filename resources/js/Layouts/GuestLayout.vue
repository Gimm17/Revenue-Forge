<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-950 px-4"
    >
        <!-- Background effects -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl"
            />
            <div
                class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-violet-500/5 rounded-full blur-3xl"
            />
        </div>

        <!-- Card (with logo inside) -->
        <div
            class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white/[0.04] border border-white/[0.08] px-8 py-8 shadow-2xl backdrop-blur-sm"
        >
            <!-- Logo inside card -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <Link href="/" class="flex items-center gap-2 group">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center shadow-lg shadow-cyan-500/20 group-hover:shadow-cyan-500/40 transition-shadow"
                    >
                        <svg
                            class="w-5 h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white"
                        >RevenueForge</span
                    >
                </Link>
            </div>

            <slot />
        </div>

        <!-- Footer -->
        <p class="relative mt-6 text-xs text-gray-600">
            &copy; {{ new Date().getFullYear() }} RevenueForge. All rights
            reserved.
        </p>
    </div>
</template>
