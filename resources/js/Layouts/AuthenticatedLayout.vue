<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    ChartBarIcon,
    TicketIcon,
    ChatBubbleBottomCenterTextIcon,
    BellIcon,
    Bars3Icon,
    XMarkIcon,
    ArrowRightOnRectangleIcon,
    UserCircleIcon,
    ShoppingBagIcon,
    UsersIcon,
    UserGroupIcon,
    MegaphoneIcon,
    Cog6ToothIcon,
    CreditCardIcon,
    GlobeAltIcon,
    ArrowTrendingUpIcon,
} from "@heroicons/vue/24/outline";

const sidebarOpen = ref(false);

const navigation = [
    {
        name: "Dashboard",
        href: route("app.dashboard"),
        current: route().current("app.dashboard"),
        icon: ChartBarIcon,
    },
    {
        name: "Offers",
        href: route("app.offers.index"),
        current: route().current("app.offers.*"),
        icon: ShoppingBagIcon,
    },
    {
        name: "Customers",
        href: route("app.customers.index"),
        current: route().current("app.customers.*"),
        icon: UsersIcon,
    },
    {
        name: "Analytics",
        href: route("app.analytics"),
        current: route().current("app.analytics*"),
        icon: ChartBarIcon,
    },
    {
        name: "Affiliates",
        href: route("app.affiliates.index"),
        current: route().current("app.affiliates.*"),
        icon: UserGroupIcon,
    },
    {
        name: "Coupons",
        href: route("app.coupons.index"),
        current: route().current("app.coupons.*"),
        icon: TicketIcon,
    },
    {
        name: "Broadcasts",
        href: route("app.broadcasts.index"),
        current: route().current("app.broadcasts.*"),
        icon: MegaphoneIcon,
    },
    {
        name: "Testimonials",
        href: route("app.testimonials.index"),
        current: route().current("app.testimonials.*"),
        icon: ChatBubbleBottomCenterTextIcon,
    },
    {
        name: "Webhooks",
        href: route("app.webhooks.index"),
        current: route().current("app.webhooks.*"),
        icon: GlobeAltIcon,
    },
    {
        name: "Notifications",
        href: route("app.notifications.index"),
        current: route().current("app.notifications.*"),
        icon: BellIcon,
    },
    {
        name: "Billing",
        href: route("app.billing"),
        current: route().current("app.billing*"),
        icon: CreditCardIcon,
    },
    {
        name: "Upgrade Plan",
        href: route("pricing"),
        current: route().current("pricing"),
        icon: ArrowTrendingUpIcon,
    },
    {
        name: "Settings",
        href: route("app.settings.edit"),
        current: route().current("app.settings.*"),
        icon: Cog6ToothIcon,
    },
];

const page = usePage();

// Notifications
const notifications = ref([]);
const unreadCount = ref(0);
const notifOpen = ref(false);
let notifInterval = null;

const fetchNotifications = async () => {
    try {
        const res = await fetch(route("app.notifications.fetch"), {
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
    <div class="min-h-screen bg-[#F8FAFC] font-['Figtree'] text-gray-900">
        <!-- Mobile Sidebar Overlay -->
        <div
            v-show="sidebarOpen"
            class="relative z-50 lg:hidden"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="fixed inset-0 bg-gray-900/80 transition-opacity"
                @click="sidebarOpen = false"
            ></div>
            <div class="fixed inset-0 flex border-r border-[#E2E8F0]">
                <div
                    class="relative mr-16 flex w-full max-w-xs flex-1 transform transition duration-300 ease-in-out"
                >
                    <div
                        class="absolute left-full top-0 flex w-16 justify-center pt-5"
                    >
                        <button
                            type="button"
                            class="-m-2.5 p-2.5"
                            @click="sidebarOpen = false"
                        >
                            <span class="sr-only">Close sidebar</span>
                            <XMarkIcon
                                class="h-6 w-6 text-white"
                                aria-hidden="true"
                            />
                        </button>
                    </div>
                    <!-- Sidebar component for mobile -->
                    <div
                        class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4"
                    >
                        <div class="flex h-16 shrink-0 items-center mt-4">
                            <Link
                                :href="route('app.dashboard')"
                                class="flex items-center gap-3"
                            >
                                <ApplicationLogo
                                    class="h-8 w-auto text-[#25f47b]"
                                />
                                <span class="text-xl font-bold tracking-tight"
                                    >Revenue Forge</span
                                >
                            </Link>
                        </div>
                        <nav class="flex flex-1 flex-col mt-4">
                            <ul
                                role="list"
                                class="flex flex-1 flex-col gap-y-7"
                            >
                                <li>
                                    <ul role="list" class="-mx-2 space-y-2">
                                        <li
                                            v-for="item in navigation"
                                            :key="item.name"
                                        >
                                            <Link
                                                :href="item.href"
                                                :class="[
                                                    item.current
                                                        ? 'bg-green-50/50 text-[#00D261] shadow-sm ring-1 ring-[#00D261]/20'
                                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                                    'group flex gap-x-3 rounded-xl p-3 text-sm leading-6 font-semibold transition-all duration-200',
                                                ]"
                                            >
                                                <component
                                                    :is="item.icon"
                                                    :class="[
                                                        item.current
                                                            ? 'text-[#00D261]'
                                                            : 'text-gray-400 group-hover:text-gray-600',
                                                        'h-6 w-6 shrink-0 transition-colors duration-200',
                                                    ]"
                                                    aria-hidden="true"
                                                />
                                                {{ item.name }}
                                            </Link>
                                        </li>
                                    </ul>
                                </li>
                                <!-- Mobile User Section -->
                                <li class="mt-auto">
                                    <div
                                        class="flex flex-col gap-4 py-4 border-t border-gray-100"
                                    >
                                        <Link
                                            :href="route('profile.edit')"
                                            class="flex items-center gap-x-4 px-2 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 rounded-xl transition-colors duration-200"
                                        >
                                            <div
                                                class="h-8 w-8 rounded-full bg-gradient-to-tr from-[#25f47b] to-[#00D261] flex items-center justify-center text-white font-bold shadow-sm"
                                            >
                                                {{
                                                    $page.props.auth.user.name.charAt(
                                                        0,
                                                    )
                                                }}
                                            </div>
                                            <span class="sr-only"
                                                >Your profile</span
                                            >
                                            <span aria-hidden="true">{{
                                                $page.props.auth.user.name
                                            }}</span>
                                        </Link>
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="flex items-center gap-x-3 px-2 py-3 text-sm font-semibold leading-6 text-gray-600 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors duration-200 w-full text-left"
                                        >
                                            <ArrowRightOnRectangleIcon
                                                class="h-5 w-5 shrink-0"
                                                aria-hidden="true"
                                            />
                                            <span>Sign out</span>
                                        </Link>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div
            class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)] border-r border-gray-100/50"
        >
            <div
                class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4"
            >
                <div
                    class="flex h-20 shrink-0 items-center justify-center mt-2"
                >
                    <Link
                        :href="route('app.dashboard')"
                        class="flex items-center gap-3 w-full justify-start px-2"
                    >
                        <div
                            class="bg-gradient-to-tr from-[#25f47b] to-[#00D261] p-1.5 rounded-xl shadow-lg shadow-[#25f47b]/20"
                        >
                            <ApplicationLogo
                                class="h-6 w-auto text-white fill-current"
                            />
                        </div>
                        <span
                            class="text-[1.35rem] font-extrabold tracking-tight text-gray-900"
                            >Revenue Forge</span
                        >
                    </Link>
                </div>
                <nav class="flex flex-1 flex-col mt-4">
                    <ul role="list" class="flex flex-1 flex-col gap-y-8">
                        <li>
                            <div
                                class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider mb-2 px-2"
                            >
                                Menu
                            </div>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li v-for="item in navigation" :key="item.name">
                                    <Link
                                        :href="item.href"
                                        :class="[
                                            item.current
                                                ? 'bg-gradient-to-r from-green-50/80 to-transparent text-[#009B48] relative before:absolute before:inset-y-0 before:left-0 before:w-1 before:bg-[#00D261] before:rounded-r-md shadow-sm'
                                                : 'text-gray-500 hover:bg-gray-50/80 hover:text-gray-900',
                                            'group flex gap-x-3 rounded-xl p-3 text-sm leading-6 font-semibold transition-all duration-200 border border-transparent',
                                        ]"
                                        :style="
                                            item.current
                                                ? 'border-color: rgba(37, 244, 123, 0.1)'
                                                : ''
                                        "
                                    >
                                        <component
                                            :is="item.icon"
                                            :class="[
                                                item.current
                                                    ? 'text-[#00D261]'
                                                    : 'text-gray-400 group-hover:text-gray-600',
                                                'h-6 w-6 shrink-0 transition-colors duration-200',
                                            ]"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </Link>
                                </li>
                            </ul>
                        </li>

                        <!-- Desktop User Section -->
                        <li class="mt-auto pt-4 border-t border-gray-100/80">
                            <div class="flex flex-col space-y-1">
                                <Link
                                    :href="route('profile.edit')"
                                    class="group flex items-center gap-x-3 rounded-xl p-2 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 transition-colors duration-200"
                                >
                                    <div
                                        class="h-9 w-9 rounded-full bg-gradient-to-tr from-[#25f47b] to-[#00D261] flex items-center justify-center text-white font-bold shadow-md shadow-[#25f47b]/20 ring-2 ring-white"
                                    >
                                        {{
                                            $page.props.auth.user.name.charAt(0)
                                        }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span aria-hidden="true">{{
                                            $page.props.auth.user.name
                                        }}</span>
                                        <span
                                            class="text-xs text-gray-500 font-medium truncate max-w-[140px]"
                                            >{{
                                                $page.props.auth.user.email
                                            }}</span
                                        >
                                    </div>
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="group flex items-center gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 text-gray-500 hover:text-red-600 hover:bg-red-50/50 transition-colors duration-200 w-full text-left"
                                >
                                    <ArrowRightOnRectangleIcon
                                        class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-red-500"
                                        aria-hidden="true"
                                    />
                                    <span>Sign out</span>
                                </Link>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:pl-72 flex flex-col min-h-screen">
            <!-- Mobile Top Bar -->
            <div
                class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white/80 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:hidden"
            >
                <button
                    type="button"
                    class="-m-2.5 p-2.5 text-gray-700"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>

                <div
                    class="flex flex-1 justify-end gap-x-4 self-stretch lg:gap-x-6"
                >
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Profile dropdown (mobile) -->
                        <div class="relative">
                            <div
                                class="h-8 w-8 rounded-full bg-gradient-to-tr from-[#25f47b] to-[#00D261] flex items-center justify-center text-white font-bold shadow-sm"
                            >
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main
                class="flex-1 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto"
            >
                <!-- Page Heading -->
                <header
                    v-if="$slots.header || $slots.actions"
                    class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4 relative w-full"
                >
                    <div v-if="$slots.header" class="flex-1 w-full sm:w-auto">
                        <slot name="header" />
                    </div>
                    <div
                        class="flex items-center gap-3 shrink-0 ml-auto pt-1 sm:pt-0"
                    >
                        <!-- Notification Dropdown -->
                        <div class="relative">
                            <button
                                @click="notifOpen = !notifOpen"
                                class="relative p-2 rounded-xl text-gray-400 hover:text-gray-900 bg-white border border-gray-100 shadow-sm hover:shadow-md hover:bg-gray-50 transition-all font-medium inline-flex items-center justify-center h-10 w-10 z-[45]"
                                title="Notifications"
                            >
                                <BellIcon class="w-5 h-5" />
                                <span
                                    v-if="unreadCount > 0"
                                    class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full shadow-md shadow-red-500/20"
                                >
                                    {{ unreadCount > 9 ? "9+" : unreadCount }}
                                </span>
                            </button>

                            <!-- Dropdown Window -->
                            <transition
                                enter-active-class="transition duration-150 ease-out"
                                enter-from-class="opacity-0 translate-y-2 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition duration-100 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 translate-y-2 scale-95"
                            >
                                <div
                                    v-if="notifOpen"
                                    class="absolute right-0 top-[calc(100%+0.5rem)] mt-2 w-[340px] bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden z-[100]"
                                >
                                    <div
                                        class="flex items-center justify-between px-5 py-4 border-b border-gray-50 bg-gray-50/50"
                                    >
                                        <h3
                                            class="text-sm font-bold text-gray-900 uppercase tracking-wider"
                                        >
                                            Notifications
                                        </h3>
                                        <button
                                            v-if="unreadCount > 0"
                                            @click="markAllRead"
                                            class="text-xs font-semibold text-[#00D261] hover:text-[#009b48] transition-colors"
                                        >
                                            Mark all read
                                        </button>
                                    </div>
                                    <div class="overflow-y-auto max-h-[350px]">
                                        <div
                                            v-for="n in notifications"
                                            :key="n.id"
                                            :class="[
                                                'px-5 py-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors',
                                                !n.read_at
                                                    ? 'bg-[#00D261]/5'
                                                    : '',
                                            ]"
                                            @click="
                                                !n.read_at && markRead(n.id)
                                            "
                                        >
                                            <div class="flex items-start gap-4">
                                                <span
                                                    class="text-2xl leading-none pt-0.5"
                                                    >{{ n.icon || "🔔" }}</span
                                                >
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-bold text-gray-900 truncate"
                                                    >
                                                        {{ n.title }}
                                                    </p>
                                                    <p
                                                        class="text-[13px] text-gray-600 mt-1 line-clamp-2 leading-relaxed"
                                                    >
                                                        {{ n.body }}
                                                    </p>
                                                    <p
                                                        class="text-[10px] uppercase tracking-wider font-bold text-gray-400 mt-2.5"
                                                    >
                                                        {{
                                                            timeAgo(
                                                                n.created_at,
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                                <span
                                                    v-if="!n.read_at"
                                                    class="w-2.5 h-2.5 rounded-full bg-[#00D261] mt-2 shrink-0 shadow-sm shadow-[#00D261]/30"
                                                />
                                            </div>
                                        </div>
                                        <div
                                            v-if="notifications.length === 0"
                                            class="px-5 py-10 text-center"
                                        >
                                            <p
                                                class="text-sm text-gray-500 font-medium"
                                            >
                                                No notifications yet
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="p-2 border-t border-gray-50 bg-gray-50/50"
                                    >
                                        <Link
                                            :href="
                                                route('app.notifications.index')
                                            "
                                            class="block w-full text-center py-2.5 text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-wider"
                                            @click="notifOpen = false"
                                        >
                                            View all notifications
                                        </Link>
                                    </div>
                                </div>
                            </transition>

                            <!-- Click outside to close -->
                            <div
                                v-if="notifOpen"
                                class="fixed inset-0 z-[50]"
                                @click="notifOpen = false"
                            />
                        </div>

                        <!-- Actions Slot -->
                        <slot name="actions" v-if="$slots.actions" />
                    </div>
                </header>

                <slot />
            </main>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap');
</style>
