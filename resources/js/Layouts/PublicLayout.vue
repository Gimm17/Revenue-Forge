<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const sidebarOpen = ref(false);

// Prevent body scroll when sidebar is open
watch(sidebarOpen, (open) => {
    document.body.style.overflow = open ? "hidden" : "";
});
</script>

<template>
    <div class="min-h-screen bg-[#0a0b10]">
        <!-- Navigation -->
        <nav
            class="fixed top-0 inset-x-0 z-50 bg-[#0a0b10]/80 backdrop-blur-xl border-b border-white/[0.06]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-400 to-violet-500 flex items-center justify-center"
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
                        <span
                            class="text-lg font-bold text-white tracking-tight"
                            >RevenueForge</span
                        >
                    </Link>

                    <!-- Desktop Nav links -->
                    <div class="hidden md:flex items-center gap-6">
                        <Link
                            href="/#features"
                            class="text-sm text-gray-400 hover:text-white transition-colors"
                            >Features</Link
                        >
                        <Link
                            href="/pricing"
                            class="text-sm text-gray-400 hover:text-white transition-colors"
                            >Pricing</Link
                        >
                    </div>

                    <!-- Desktop CTA -->
                    <div class="hidden sm:flex items-center gap-3">
                        <Link
                            href="/login"
                            class="text-sm text-gray-300 hover:text-white transition-colors px-4 py-2"
                        >
                            Sign In
                        </Link>
                        <Link
                            href="/register"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
                        >
                            Get Started
                        </Link>
                    </div>

                    <!-- Mobile hamburger button -->
                    <button
                        class="sm:hidden flex items-center justify-center w-10 h-10 rounded-lg text-gray-400 hover:text-white hover:bg-white/[0.06] transition-all"
                        @click="sidebarOpen = true"
                        aria-label="Open menu"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Sidebar Overlay -->
        <transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm sm:hidden"
                @click="sidebarOpen = false"
            />
        </transition>

        <!-- Mobile Sidebar Panel -->
        <transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="sidebarOpen"
                class="fixed top-0 right-0 bottom-0 z-[70] w-72 bg-[#0d0e14] border-l border-white/[0.08] shadow-2xl sm:hidden flex flex-col"
            >
                <!-- Sidebar Header -->
                <div
                    class="flex items-center justify-between h-16 px-5 border-b border-white/[0.06]"
                >
                    <Link
                        href="/"
                        class="flex items-center gap-2"
                        @click="sidebarOpen = false"
                    >
                        <div
                            class="w-7 h-7 rounded-lg bg-gradient-to-br from-cyan-400 to-violet-500 flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-white"
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
                        <span class="text-base font-bold text-white"
                            >RevenueForge</span
                        >
                    </Link>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-white/[0.06] transition-all"
                        @click="sidebarOpen = false"
                        aria-label="Close menu"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Links -->
                <div class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <Link
                        href="/#features"
                        class="flex items-center gap-3 px-3 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/[0.06] rounded-lg transition-colors"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                            />
                        </svg>
                        Features
                    </Link>
                    <Link
                        href="/pricing"
                        class="flex items-center gap-3 px-3 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/[0.06] rounded-lg transition-colors"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        Pricing
                    </Link>
                </div>

                <!-- Sidebar Bottom -->
                <div class="px-4 py-4 border-t border-white/[0.06] space-y-2">
                    <Link
                        href="/login"
                        class="flex items-center justify-center w-full px-4 py-2.5 text-sm text-gray-300 hover:text-white border border-white/[0.1] rounded-lg hover:bg-white/[0.06] transition-all"
                        @click="sidebarOpen = false"
                    >
                        Sign In
                    </Link>
                    <Link
                        href="/register"
                        class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
                        @click="sidebarOpen = false"
                    >
                        Get Started
                    </Link>
                </div>
            </div>
        </transition>

        <!-- Page content -->
        <main class="pt-16">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-white/[0.06] bg-[#0a0b10]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div
                    class="flex flex-col md:flex-row items-center justify-between gap-4"
                >
                    <div class="flex items-center gap-2">
                        <div
                            class="w-6 h-6 rounded-md bg-gradient-to-br from-cyan-400 to-violet-500 flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-white"
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
                        <span class="text-sm font-semibold text-white"
                            >RevenueForge</span
                        >
                    </div>
                    <p class="text-xs text-gray-500">
                        &copy; {{ new Date().getFullYear() }} RevenueForge. All
                        rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
