<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({ email: String });
</script>

<template>
    <Head title="Magic Link Simulator" />
    <div class="min-h-screen bg-gray-900 flex items-center justify-center p-4">
        <div
            class="max-w-md w-full bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-700"
        >
            <!-- Sandbox Banner -->
            <div class="bg-amber-500/10 border-b border-amber-500/20 px-6 py-3">
                <div class="flex items-center gap-2">
                    <svg
                        class="w-5 h-5 text-amber-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        ></path>
                    </svg>
                    <p class="text-xs font-medium text-amber-400">
                        SANDBOX MODE • NO REAL LOGIN
                    </p>
                </div>
            </div>

            <div class="p-8 text-center">
                <div
                    class="w-16 h-16 bg-cyan-500/10 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <svg
                        class="w-8 h-8 text-cyan-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        ></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-white mb-2">
                    You've got mail!
                </h2>
                <p class="text-gray-400 mb-6">
                    In a live environment, an email with a secure, magic login
                    link granting access to the Customer Portal would be sent
                    to:
                    <br /><span class="text-white font-semibold mt-2 block">{{
                        email
                    }}</span>
                </p>

                <div
                    class="bg-white/[0.03] p-4 rounded-xl border border-white/[0.05] mb-8 text-left"
                >
                    <p class="text-sm text-gray-400 mb-2">
                        Subject: Your RevenueForge Access Link
                    </p>
                    <p
                        class="text-sm text-gray-300 border-t border-white/[0.05] pt-2"
                    >
                        Hi,<br /><br />
                        Click the button below to access your purchases and
                        manage your subscriptions.<br /><br />
                        <a
                            href="#"
                            class="inline-block mt-3 px-4 py-2 bg-cyan-500 text-white text-sm font-medium rounded-lg opacity-80 cursor-not-allowed"
                            >Access My Portal</a
                        >
                    </p>
                </div>

                <Link
                    href="/"
                    class="block w-full text-center px-4 py-3 bg-white/[0.05] hover:bg-white/[0.1] text-white text-sm font-medium rounded-xl transition-colors border border-white/[0.1]"
                >
                    Return to Homepage
                </Link>
            </div>
        </div>
    </div>
</template>
