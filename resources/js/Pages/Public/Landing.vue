<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const features = [
    {
        title: "AI Offer Studio",
        description:
            "Generate high-converting offers with AI. Get titles, pitch copy, benefits, FAQs, and pricing suggestions in seconds.",
        icon: "sparkles",
    },
    {
        title: "Mayar Payments",
        description:
            "Accept payments seamlessly with Mayar-powered checkout. Support one-time, subscriptions, and credit packs.",
        icon: "credit-card",
    },
    {
        title: "Webhook Automation",
        description:
            "Automatic access provisioning when payments are confirmed. No manual work needed.",
        icon: "zap",
    },
    {
        title: "Revenue Dashboard",
        description:
            "Track gross revenue, orders, subscriptions, credits sold, and customer growth in real-time.",
        icon: "chart",
    },
    {
        title: "Customer Portal",
        description:
            "Send magic links to your customers for secure portal access. Manage access and subscriptions.",
        icon: "users",
    },
    {
        title: "Affiliate Tracking",
        description:
            "Track referral links, clicks, and conversions. Attribute revenue to affiliates automatically.",
        icon: "link",
    },
];
</script>

<template>
    <Head title="AI Revenue OS for Creators and UMKM" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <!-- Background effects -->
            <div class="absolute inset-0">
                <div
                    class="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl"
                />
                <div
                    class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl"
                />
            </div>

            <div
                class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40"
            >
                <div class="text-center max-w-4xl mx-auto">
                    <!-- Badge -->
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-medium mb-6"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"
                        />
                        AI-Powered Revenue OS
                    </div>

                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6"
                    >
                        Turn Your Ideas Into
                        <span
                            class="bg-gradient-to-r from-cyan-400 to-violet-400 bg-clip-text text-transparent"
                        >
                            Revenue Machines</span
                        >
                    </h1>

                    <p
                        class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed"
                    >
                        Generate offers with AI, accept payments through Mayar,
                        automate access delivery, and track everything from one
                        dashboard. Built for creators and UMKM.
                    </p>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-center gap-4"
                    >
                        <Link
                            href="/register"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40"
                        >
                            Start Building Free
                            <svg
                                class="w-5 h-5 ml-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />
                            </svg>
                        </Link>
                        <Link
                            href="/pricing"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-medium text-gray-300 border border-white/10 rounded-xl hover:bg-white/[0.06] transition-all"
                        >
                            View Pricing
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 border-t border-white/[0.06]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                        Everything You Need to Monetize
                    </h2>
                    <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                        From AI-powered offer creation to automated payment
                        processing, RevenueForge handles the entire revenue
                        lifecycle.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="group relative rounded-2xl bg-gradient-to-br from-white/[0.06] to-white/[0.02] border border-white/[0.08] p-6 hover:border-cyan-500/30 transition-all duration-300"
                    >
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-cyan-500/5 to-violet-500/5 opacity-0 group-hover:opacity-100 transition-opacity"
                        />

                        <div class="relative">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500/20 to-violet-500/20 flex items-center justify-center mb-4"
                            >
                                <svg
                                    v-if="feature.icon === 'sparkles'"
                                    class="w-5 h-5 text-cyan-400"
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
                                <svg
                                    v-else-if="feature.icon === 'credit-card'"
                                    class="w-5 h-5 text-cyan-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                    />
                                </svg>
                                <svg
                                    v-else-if="feature.icon === 'zap'"
                                    class="w-5 h-5 text-cyan-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                                <svg
                                    v-else-if="feature.icon === 'chart'"
                                    class="w-5 h-5 text-cyan-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    />
                                </svg>
                                <svg
                                    v-else-if="feature.icon === 'users'"
                                    class="w-5 h-5 text-cyan-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <svg
                                    v-else-if="feature.icon === 'link'"
                                    class="w-5 h-5 text-cyan-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                    />
                                </svg>
                            </div>

                            <h3 class="text-lg font-semibold text-white mb-2">
                                {{ feature.title }}
                            </h3>
                            <p class="text-sm text-gray-400 leading-relaxed">
                                {{ feature.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 border-t border-white/[0.06]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Ready to Forge Your Revenue?
                </h2>
                <p class="text-lg text-gray-400 mb-8">
                    Start creating AI-powered offers and accept payments in
                    minutes. No complex setup required.
                </p>
                <Link
                    href="/register"
                    class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-xl shadow-cyan-500/25"
                >
                    Get Started Now
                    <svg
                        class="w-5 h-5 ml-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"
                        />
                    </svg>
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
