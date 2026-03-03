<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
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
        name: "Coupons",
        href: "/app/coupons",
        icon: "coupons",
        routePrefix: "app.coupons",
    },
    {
        name: "Broadcasts",
        href: "/app/broadcasts",
        icon: "broadcasts",
        routePrefix: "app.broadcasts",
    },
    {
        name: "Testimonials",
        href: "/app/testimonials",
        icon: "testimonials",
        routePrefix: "app.testimonials",
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

// Notifications
const notifications = ref([]);
const unreadCount = ref(0);
const notifOpen = ref(false);
let notifInterval = null;

const fetchNotifications = async () => {
    try {
        const res = await fetch("/app/notifications", {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        if (res.ok) {
            const data = await res.json();
            notifications.value = data.notifications || [];
            unreadCount.value = data.unread_count || 0;
        }
    } catch (e) {
        /* silent */
    }
};

const markRead = async (id) => {
    const csrf = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    await fetch(`/app/notifications/${id}/read`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrf, Accept: "application/json" },
    });
    fetchNotifications();
};

const markAllRead = async () => {
    const csrf = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    await fetch("/app/notifications/read-all", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrf, Accept: "application/json" },
    });
    fetchNotifications();
};

const timeAgo = (dateStr) => {
    const diff = Date.now() - new Date(dateStr).getTime();
    const mins = Math.floor(diff / 60000);
    if (mins < 1) return "just now";
    if (mins < 60) return `${mins}m ago`;
    const hrs = Math.floor(mins / 60);
    if (hrs < 24) return `${hrs}h ago`;
    return `${Math.floor(hrs / 24)}d ago`;
};

onMounted(() => {
    fetchNotifications();
    notifInterval = setInterval(fetchNotifications, 30000);
});

onUnmounted(() => {
    if (notifInterval) clearInterval(notifInterval);
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
                    <!-- Coupons icon -->
                    <svg
                        v-else-if="item.icon === 'coupons'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                        />
                    </svg>
                    <!-- Broadcasts icon -->
                    <svg
                        v-else-if="item.icon === 'broadcasts'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                    </svg>
                    <!-- Testimonials icon -->
                    <svg
                        v-else-if="item.icon === 'testimonials'"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
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
                    <!-- Notification Bell -->
                    <div class="relative">
                        <button
                            @click="notifOpen = !notifOpen"
                            class="relative p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/[0.06] transition-colors"
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
                                    stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                />
                            </svg>
                            <span
                                v-if="unreadCount > 0"
                                class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full min-w-[18px] h-[18px] leading-none"
                            >
                                {{ unreadCount > 9 ? "9+" : unreadCount }}
                            </span>
                        </button>

                        <!-- Notification Dropdown -->
                        <transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="notifOpen"
                                class="absolute right-0 top-full mt-2 w-80 max-h-96 bg-[#1a1c27] border border-white/[0.08] rounded-xl shadow-2xl overflow-hidden z-50"
                            >
                                <div
                                    class="flex items-center justify-between px-4 py-3 border-b border-white/[0.06]"
                                >
                                    <h3
                                        class="text-sm font-semibold text-white"
                                    >
                                        Notifications
                                    </h3>
                                    <button
                                        v-if="unreadCount > 0"
                                        @click="markAllRead"
                                        class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors"
                                    >
                                        Mark all read
                                    </button>
                                </div>
                                <div class="overflow-y-auto max-h-72">
                                    <div
                                        v-for="n in notifications"
                                        :key="n.id"
                                        :class="[
                                            'px-4 py-3 border-b border-white/[0.04] hover:bg-white/[0.04] cursor-pointer transition-colors',
                                            !n.read_at
                                                ? 'bg-cyan-500/[0.04]'
                                                : '',
                                        ]"
                                        @click="!n.read_at && markRead(n.id)"
                                    >
                                        <div class="flex items-start gap-3">
                                            <span
                                                class="text-lg leading-none mt-0.5"
                                                >{{ n.icon || "💰" }}</span
                                            >
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-medium text-white truncate"
                                                >
                                                    {{ n.title }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-400 truncate"
                                                >
                                                    {{ n.body }}
                                                </p>
                                                <p
                                                    class="text-[10px] text-gray-600 mt-1"
                                                >
                                                    {{ timeAgo(n.created_at) }}
                                                </p>
                                            </div>
                                            <span
                                                v-if="!n.read_at"
                                                class="w-2 h-2 rounded-full bg-cyan-400 mt-1.5 shrink-0"
                                            />
                                        </div>
                                    </div>
                                    <div
                                        v-if="notifications.length === 0"
                                        class="px-4 py-8 text-center"
                                    >
                                        <p class="text-sm text-gray-500">
                                            No notifications yet
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </transition>

                        <!-- Click outside to close -->
                        <div
                            v-if="notifOpen"
                            class="fixed inset-0 z-40"
                            @click="notifOpen = false"
                        />
                    </div>

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
