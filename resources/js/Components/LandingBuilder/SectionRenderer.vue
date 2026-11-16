<script setup>
/**
 * SectionRenderer — renders a single landing page section by type.
 * Used in both the builder preview and the public offer page.
 */
const props = defineProps({
    section: { type: Object, required: true },
    offer: { type: Object, default: null },
    editable: { type: Boolean, default: false },
});

const emit = defineEmits(["checkout"]);

const formatCurrency = (amount, currency = "IDR") =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency,
        minimumFractionDigits: 0,
    }).format(amount || 0);
</script>

<template>
    <!-- HERO -->
    <section
        v-if="section.type === 'hero'"
        class="relative py-20 md:py-32 overflow-hidden transition-colors"
        :class="{
            'bg-white': section.content.background === 'white',
            'bg-gray-50': section.content.background === 'gray',
            'bg-gradient-to-br from-[#25f47b]/10 via-[#00D261]/5 to-transparent':
                section.content.background === 'gradient' ||
                !section.content.background,
        }"
    >
        <div
            class="relative max-w-5xl mx-auto px-6"
            :class="{
                'text-center':
                    section.content.alignment !== 'left' &&
                    section.content.alignment !== 'right',
                'text-left': section.content.alignment === 'left',
                'text-right': section.content.alignment === 'right',
            }"
        >
            <h1
                class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-[1.15] tracking-tight"
            >
                {{ section.content.headline }}
            </h1>
            <p
                v-if="section.content.subtitle"
                class="text-lg md:text-xl text-gray-500 mb-10 max-w-3xl"
                :class="{
                    'mx-auto':
                        section.content.alignment !== 'left' &&
                        section.content.alignment !== 'right',
                    'ml-auto': section.content.alignment === 'right',
                }"
            >
                {{ section.content.subtitle }}
            </p>
            <button
                @click="emit('checkout')"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold transition-all shadow-xl hover:scale-105"
                :class="{
                    'text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] shadow-[#25f47b]/30 rounded-xl':
                        section.content.button_style === 'primary' ||
                        !section.content.button_style,
                    'text-gray-900 bg-white border border-gray-200 shadow-gray-200/50 rounded-xl':
                        section.content.button_style === 'outline',
                    'text-white bg-gray-900 shadow-gray-900/30 rounded-full':
                        section.content.button_style === 'secondary',
                }"
            >
                {{ section.content.cta_text || "Get Started" }}
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
                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                    />
                </svg>
            </button>
        </div>
    </section>

    <!-- TEXT -->
    <section
        v-else-if="section.type === 'text'"
        class="py-16 md:py-24 transition-colors"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-gradient-to-br from-[#25f47b]/5 to-transparent':
                section.content.background === 'gradient',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div
            class="max-w-3xl mx-auto px-6"
            :class="{
                'text-center': section.content.alignment === 'center',
                'text-left':
                    section.content.alignment === 'left' ||
                    !section.content.alignment,
                'text-right': section.content.alignment === 'right',
            }"
        >
            <p
                class="text-lg md:text-xl text-gray-600 leading-relaxed whitespace-pre-line font-medium"
            >
                {{ section.content.body }}
            </p>
        </div>
    </section>

    <!-- BENEFITS -->
    <section
        v-else-if="section.type === 'benefits'"
        class="py-16 md:py-24 transition-colors"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-gradient-to-br from-[#25f47b]/5 to-transparent':
                section.content.background === 'gradient',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="(item, i) in section.content.items"
                    :key="i"
                    class="flex items-start gap-4 p-6 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div
                        class="flex-shrink-0 w-10 h-10 rounded-full bg-[#00D261]/10 flex items-center justify-center text-[#00D261] text-lg font-bold"
                    >
                        {{ item.icon || "✓" }}
                    </div>
                    <span class="text-gray-700 font-medium pt-2 leading-snug">{{
                        item.text
                    }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section
        v-else-if="section.type === 'pricing'"
        class="py-16 md:py-24 transition-colors"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-gradient-to-br from-[#25f47b]/5 to-transparent':
                section.content.background === 'gradient',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div class="max-w-lg mx-auto px-6 text-center">
            <div
                class="rounded-3xl bg-white border border-gray-100 shadow-2xl shadow-gray-200/50 p-10 relative overflow-hidden"
            >
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-[#25f47b] to-[#00D261]"
                />
                <p
                    v-if="section.content.label"
                    class="inline-block px-3 py-1 mb-6 text-xs font-bold text-[#00D261] tracking-wider uppercase bg-[#00D261]/10 rounded-full"
                >
                    {{ section.content.label }}
                </p>
                <div class="flex items-end justify-center gap-1 mb-8">
                    <span
                        class="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight"
                        >{{
                            formatCurrency(
                                section.content.price,
                                section.content.currency,
                            )
                        }}</span
                    >
                    <span
                        v-if="section.content.interval"
                        class="text-lg text-gray-500 font-medium pb-2"
                    >
                        /
                        {{
                            section.content.interval === "monthly"
                                ? "bulan"
                                : "tahun"
                        }}
                    </span>
                </div>
                <button
                    @click="emit('checkout')"
                    class="w-full py-4 text-base font-bold text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] rounded-xl hover:scale-105 transition-all shadow-lg shadow-[#25f47b]/30"
                >
                    Buy Now
                </button>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section
        v-else-if="section.type === 'faq'"
        class="py-16 md:py-24 transition-colors"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-gradient-to-br from-[#25f47b]/5 to-transparent':
                section.content.background === 'gradient',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div class="max-w-3xl mx-auto px-6">
            <h2
                class="text-3xl font-extrabold text-gray-900 mb-10 text-center tracking-tight"
            >
                Frequently Asked Questions
            </h2>
            <div class="space-y-4">
                <details
                    v-for="(f, i) in section.content.items"
                    :key="i"
                    class="group rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
                >
                    <summary
                        class="cursor-pointer px-6 py-5 text-base font-semibold text-gray-900 flex items-center justify-between outline-none"
                    >
                        {{ f.question }}
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-[#00D261]/10 group-hover:text-[#00D261] transition-colors"
                        >
                            <svg
                                class="w-4 h-4 group-open:rotate-180 transition-transform"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </div>
                    </summary>
                    <div
                        class="px-6 pb-5 pt-1 text-sm text-gray-600 leading-relaxed border-t border-gray-50"
                    >
                        {{ f.answer }}
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- TESTIMONIAL -->
    <section
        v-else-if="section.type === 'testimonial'"
        class="py-16 md:py-24 transition-colors"
        :class="{
            'bg-white': section.content.background === 'white',
            'bg-gradient-to-br from-[#25f47b]/5 to-transparent':
                section.content.background === 'gradient',
            'bg-gray-50':
                section.content.background === 'gray' ||
                !section.content.background,
        }"
    >
        <div class="max-w-6xl mx-auto px-6">
            <h2
                class="text-3xl font-extrabold text-gray-900 mb-10 text-center tracking-tight"
            >
                What People Say
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="(t, i) in section.content.items"
                    :key="i"
                    class="p-8 rounded-3xl bg-white border border-gray-100 shadow-sm hover:shadow-lg transition-shadow relative"
                >
                    <div
                        class="text-4xl text-[#00D261]/20 font-serif absolute top-6 right-8"
                    >
                        "
                    </div>
                    <p
                        class="text-gray-700 text-base font-medium italic mb-6 relative z-10 leading-relaxed"
                    >
                        "{{ t.quote }}"
                    </p>
                    <div
                        class="flex items-center gap-4 border-t border-gray-50 pt-4 mt-auto"
                    >
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-[#25f47b] to-[#00D261] flex items-center justify-center text-lg text-white font-bold shadow-md"
                        >
                            {{ (t.name || "?").charAt(0) }}
                        </div>
                        <div>
                            <p class="text-base text-gray-900 font-bold">
                                {{ t.name }}
                            </p>
                            <p
                                v-if="t.role"
                                class="text-xs text-gray-500 font-medium tracking-wide"
                            >
                                {{ t.role }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section
        v-else-if="section.type === 'cta'"
        class="py-20 md:py-28 text-center transition-colors"
        :class="{
            'bg-white': section.content.background === 'white',
            'bg-gray-50': section.content.background === 'gray',
            'bg-gradient-to-r from-[#25f47b]/10 to-[#00D261]/10':
                section.content.background === 'gradient' ||
                !section.content.background,
            'bg-gray-900 text-white': section.content.background === 'dark',
        }"
    >
        <div
            class="max-w-4xl mx-auto px-6"
            :class="{
                'text-center':
                    section.content.alignment !== 'left' &&
                    section.content.alignment !== 'right',
                'text-left': section.content.alignment === 'left',
                'text-right': section.content.alignment === 'right',
            }"
        >
            <h2
                class="text-3xl md:text-5xl font-extrabold mb-8 tracking-tight"
                :class="
                    section.content.background === 'dark'
                        ? 'text-white'
                        : 'text-gray-900'
                "
            >
                {{ section.content.headline }}
            </h2>
            <button
                @click="emit('checkout')"
                class="inline-flex items-center justify-center px-10 py-4 text-lg font-bold transition-all shadow-xl hover:scale-105"
                :class="{
                    'text-white bg-gradient-to-r from-[#25f47b] to-[#00D261] shadow-[#25f47b]/30 rounded-xl':
                        section.content.button_style === 'primary' ||
                        !section.content.button_style,
                    'text-gray-900 bg-white border border-gray-200 shadow-gray-200/50 rounded-xl':
                        section.content.button_style === 'white',
                    'text-white bg-gray-900 shadow-gray-900/30 rounded-full':
                        section.content.button_style === 'dark' ||
                        (section.content.button_style === 'outline' &&
                            section.content.background !== 'dark'),
                    'text-white border-2 border-white hover:bg-white hover:text-gray-900 rounded-xl':
                        section.content.button_style === 'outline' &&
                        section.content.background === 'dark',
                }"
            >
                {{ section.content.button_text || "Get Started" }}
            </button>
        </div>
    </section>

    <!-- IMAGE -->
    <section
        v-else-if="section.type === 'image'"
        class="py-12 md:py-16 transition-colors"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div class="max-w-5xl mx-auto px-6">
            <figure
                class="rounded-2xl overflow-hidden shadow-2xl shadow-gray-200/50 border border-gray-100 bg-white"
            >
                <img
                    :src="
                        section.content.url ||
                        'https://placehold.co/1200x600/f8fafc/cbd5e1?text=Your+Image'
                    "
                    :alt="section.content.alt || 'Image'"
                    class="w-full h-auto object-cover"
                />
                <figcaption
                    v-if="section.content.caption"
                    class="text-center text-sm text-gray-500 py-3 bg-gray-50 border-t border-gray-100 font-medium"
                >
                    {{ section.content.caption }}
                </figcaption>
            </figure>
        </div>
    </section>

    <!-- VIDEO -->
    <section
        v-else-if="section.type === 'video'"
        class="py-12 md:py-16 transition-colors"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div class="max-w-4xl mx-auto px-6">
            <div
                class="aspect-video rounded-3xl overflow-hidden border border-gray-100 shadow-2xl shadow-gray-200/50 bg-gray-900"
            >
                <iframe
                    v-if="section.content.url"
                    :src="
                        section.content.url
                            ?.replace('watch?v=', 'embed/')
                            .replace('youtu.be/', 'www.youtube.com/embed/')
                    "
                    class="w-full h-full"
                    frameborder="0"
                    allow="
                        accelerometer;
                        autoplay;
                        clipboard-write;
                        encrypted-media;
                        gyroscope;
                        picture-in-picture;
                    "
                    allowfullscreen
                />
                <div
                    v-else
                    class="w-full h-full flex flex-col gap-3 items-center justify-center text-gray-500"
                >
                    <svg
                        class="w-12 h-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                        ></path>
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <span class="font-medium text-sm"
                        >Paste a YouTube or Vimeo URL in Editor</span
                    >
                </div>
            </div>
        </div>
    </section>

    <!-- DIVIDER -->
    <section
        v-else-if="section.type === 'divider'"
        class="py-8"
        :class="{
            'bg-gray-50': section.content.background === 'gray',
            'bg-white':
                section.content.background === 'white' ||
                !section.content.background,
        }"
    >
        <div class="max-w-5xl mx-auto px-6">
            <hr
                v-if="
                    !section.content.style || section.content.style === 'line'
                "
                class="border-gray-200"
            />
            <div
                v-else-if="section.content.style === 'dots'"
                class="flex justify-center gap-3"
            >
                <span class="w-2 h-2 rounded-full bg-gray-300" />
                <span class="w-2 h-2 rounded-full bg-gray-300" />
                <span class="w-2 h-2 rounded-full bg-gray-300" />
            </div>
            <div v-else class="h-12" />
        </div>
    </section>
</template>
