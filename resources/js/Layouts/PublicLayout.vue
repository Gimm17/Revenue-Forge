<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const mobileMenuOpen = ref(false);
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
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        aria-label="Toggle menu"
                    >
                        <!-- Hamburger icon -->
                        <svg
                            v-if="!mobileMenuOpen"
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
                        <!-- Close icon -->
                        <svg
                            v-else
                            class="w-6 h-6"
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
            </div>

            <!-- Mobile Menu Panel -->
            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div
                    v-if="mobileMenuOpen"
                    class="sm:hidden bg-[#0a0b10]/95 backdrop-blur-xl border-b border-white/[0.06]"
                >
                    <div class="px-4 py-4 space-y-2">
                        <Link
                            href="/#features"
                            class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/[0.06] rounded-lg transition-colors"
                            @click="mobileMenuOpen = false"
                        >
                            Features
                        </Link>
                        <Link
                            href="/pricing"
                            class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/[0.06] rounded-lg transition-colors"
                            @click="mobileMenuOpen = false"
                        >
                            Pricing
                        </Link>

                        <div
                            class="border-t border-white/[0.06] pt-3 mt-3 space-y-2"
                        >
                            <Link
                                href="/login"
                                class="block px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/[0.06] rounded-lg transition-colors"
                                @click="mobileMenuOpen = false"
                            >
                                Sign In
                            </Link>
                            <Link
                                href="/register"
                                class="block px-4 py-3 text-sm font-medium text-center text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
                                @click="mobileMenuOpen = false"
                            >
                                Get Started
                            </Link>
                        </div>
                    </div>
                </div>
            </transition>
        </nav>

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
