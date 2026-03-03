<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const page = usePage();
const user = computed(() => page.props.auth.user);
const workspace = computed(() => page.props.workspace);

const sidebarOpen = ref(false);

const navigation = [
    {
        name: "Dashboard",
        href: "/app",
        icon: "dashboard",
        routePrefix: "app.dashboard",
    },
    {
        name: "Offers",
        href: "/app/offers",
        icon: "offers",
        routePrefix: "app.offers",
    },
    {
        name: "Customers",
        href: "/app/customers",
        icon: "customers",
        routePrefix: "app.customers",
    },
    {
        name: "Analytics",
        href: "/app/analytics",
        icon: "analytics",
        routePrefix: "app.analytics",
    },
    {
        name: "Affiliates",
        href: "/app/affiliates",
        icon: "affiliates",
        routePrefix: "app.affiliates",
    },
    {
        name: "Billing",
        href: "/app/billing",
        icon: "billing",
        routePrefix: "app.billing",
    },
    {
        name: "Settings",
        href: "/app/settings",
        icon: "settings",
        routePrefix: "app.settings",
    },
];

const isActive = (item) => {
    const path = window.location.pathname;
    if (item.href === "/app") return path === "/app";
    return path.startsWith(item.href);
};

const userInitials = computed(() => {
    const name = user.value?.name || "";
    return name
        .split(" ")
        .map((w) => w[0])
        .slice(0, 2)
        .join("")
        .toUpperCase();
});
</script>

<template>
    <div class="min-h-screen bg-[#0f1117]">
        <!-- Mobile sidebar backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
            class="flex flex-col bg-[#13151d] border-r border-white/[0.06]"
        >
            <!-- Logo area -->
            <div
                class="flex items-center h-16 px-6 border-b border-white/[0.06]"
            >
                <Link href="/app" class="flex items-center gap-3">
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
                    <span class="text-lg font-bold text-white tracking-tight"
                        >RevenueForge</span
                    >
                </Link>
            </div>

            <!-- Workspace indicator -->
            <div
                v-if="workspace"
                class="px-4 py-3 border-b border-white/[0.06]"
            >
                <div
                    class="flex items-center gap-2 px-2 py-1.5 rounded-lg bg-white/[0.04]"
                >
                    <div
                        class="w-6 h-6 rounded-md flex items-center justify-center text-xs font-bold text-white"
                        :style="{
                            backgroundColor: workspace.brand_color || '#06b6d4',
                        }"
                    >
                        {{ workspace.name?.[0]?.toUpperCase() }}
                    </div>
                    <span class="text-sm text-gray-300 truncate">{{
                        workspace.name
                    }}</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200',
                        isActive(item)
                            ? 'bg-gradient-to-r from-cyan-500/15 to-violet-500/10 text-cyan-400 shadow-sm shadow-cyan-500/5'
                            : 'text-gray-400 hover:text-white hover:bg-white/[0.06]',
                    ]"
                    @click="sidebarOpen = false"
                >
                    <!-- Dashboard icon -->
                    <svg
                        v-if="item.icon === 'dashboard'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        />
                    </svg>
                    <!-- Offers icon -->
                    <svg
                        v-else-if="item.icon === 'offers'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                        />
                    </svg>
                    <!-- Customers icon -->
                    <svg
                        v-else-if="item.icon === 'customers'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    <!-- Analytics icon -->
                    <svg
                        v-else-if="item.icon === 'analytics'"
                        class="w-5 h-5"
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
                    <!-- Affiliates icon -->
                    <svg
                        v-else-if="item.icon === 'affiliates'"
                        class="w-5 h-5"
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
                    <!-- Billing icon -->
                    <svg
                        v-else-if="item.icon === 'billing'"
                        class="w-5 h-5"
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
                    <!-- Settings icon -->
                    <svg
                        v-else-if="item.icon === 'settings'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    {{ item.name }}
                </Link>
            </nav>

            <!-- User section at bottom -->
            <div class="p-3 border-t border-white/[0.06]">
                <Dropdown align="top-left" width="48">
                    <template #trigger>
                        <button
                            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg hover:bg-white/[0.06] transition-colors"
                        >
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center text-xs font-bold text-white"
                            >
                                {{ userInitials }}
                            </div>
                            <div class="flex-1 text-left min-w-0">
                                <p
                                    class="text-sm font-medium text-white truncate"
                                >
                                    {{ user?.name }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ user?.email }}
                                </p>
                            </div>
                            <svg
                                class="w-4 h-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4"
                                />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')"
                            >Profile</DropdownLink
                        >
                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                            >Log Out</DropdownLink
                        >
                    </template>
                </Dropdown>
            </div>
        </aside>

        <!-- Main content area -->
        <div class="lg:pl-64">
            <!-- Top bar (mobile) -->
            <header
                class="sticky top-0 z-30 flex items-center h-16 px-4 bg-[#0f1117]/80 backdrop-blur-xl border-b border-white/[0.06] lg:px-8"
            >
                <!-- Mobile menu button -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 -ml-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/[0.06] transition-colors"
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

                <!-- Page title slot -->
                <div class="flex-1 ml-2 lg:ml-0">
                    <slot name="header" />
                </div>

                <!-- Right side actions -->
                <div class="flex items-center gap-3">
                    <slot name="actions" />
                </div>
            </header>

            <!-- Page content -->
            <main class="p-4 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
