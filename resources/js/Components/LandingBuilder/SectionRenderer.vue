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
        class="relative py-20 md:py-28 overflow-hidden"
    >
        <div
            class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-violet-500/5"
        />
        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <h1
                class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight"
            >
                {{ section.content.headline }}
            </h1>
            <p
                v-if="section.content.subtitle"
                class="text-lg md:text-xl text-gray-400 mb-8"
            >
                {{ section.content.subtitle }}
            </p>
            <button
                @click="emit('checkout')"
                class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40 hover:scale-[1.02]"
            >
                {{ section.content.cta_text || "Buy Now" }}
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
        class="max-w-2xl mx-auto px-6 py-12"
    >
        <p class="text-lg text-gray-300 leading-relaxed whitespace-pre-line">
            {{ section.content.body }}
        </p>
    </section>

    <!-- BENEFITS -->
    <section
        v-else-if="section.type === 'benefits'"
        class="max-w-4xl mx-auto px-6 py-12"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
                v-for="(item, i) in section.content.items"
                :key="i"
                class="flex items-start gap-3 p-4 rounded-xl bg-white/[0.04] border border-white/[0.08]"
            >
                <span class="text-cyan-400 text-lg shrink-0">{{
                    item.icon || "✓"
                }}</span>
                <span class="text-gray-200 text-sm">{{ item.text }}</span>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section
        v-else-if="section.type === 'pricing'"
        class="max-w-lg mx-auto px-6 py-12 text-center"
    >
        <div class="rounded-2xl bg-white/[0.04] border border-white/[0.08] p-8">
            <p v-if="section.content.label" class="text-sm text-gray-400 mb-2">
                {{ section.content.label }}
            </p>
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="text-4xl md:text-5xl font-bold text-white">{{
                    formatCurrency(
                        section.content.price,
                        section.content.currency,
                    )
                }}</span>
                <span
                    v-if="section.content.interval"
                    class="text-sm text-gray-500"
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
                class="w-full py-3 text-sm font-bold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all"
            >
                Buy Now
            </button>
        </div>
    </section>

    <!-- FAQ -->
    <section
        v-else-if="section.type === 'faq'"
        class="max-w-2xl mx-auto px-6 py-12"
    >
        <h2 class="text-xl font-bold text-white mb-6 text-center">
            Frequently Asked Questions
        </h2>
        <div class="space-y-3">
            <details
                v-for="(f, i) in section.content.items"
                :key="i"
                class="group rounded-xl bg-white/[0.04] border border-white/[0.08]"
            >
                <summary
                    class="cursor-pointer px-5 py-4 text-sm font-medium text-white flex items-center justify-between"
                >
                    {{ f.question }}
                    <svg
                        class="w-4 h-4 text-gray-500 group-open:rotate-180 transition-transform"
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
                </summary>
                <div class="px-5 pb-4 text-sm text-gray-400">
                    {{ f.answer }}
                </div>
            </details>
        </div>
    </section>

    <!-- TESTIMONIAL -->
    <section
        v-else-if="section.type === 'testimonial'"
        class="max-w-4xl mx-auto px-6 py-12"
    >
        <h2 class="text-xl font-bold text-white mb-6 text-center">
            What People Say
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
                v-for="(t, i) in section.content.items"
                :key="i"
                class="p-5 rounded-xl bg-white/[0.04] border border-white/[0.08]"
            >
                <p class="text-gray-300 text-sm italic mb-3">"{{ t.quote }}"</p>
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center text-xs text-white font-bold"
                    >
                        {{ (t.name || "?").charAt(0) }}
                    </div>
                    <div>
                        <p class="text-sm text-white font-medium">
                            {{ t.name }}
                        </p>
                        <p v-if="t.role" class="text-xs text-gray-500">
                            {{ t.role }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section
        v-else-if="section.type === 'cta'"
        class="max-w-4xl mx-auto px-6 py-16 text-center"
    >
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-6">
            {{ section.content.headline }}
        </h2>
        <button
            @click="emit('checkout')"
            class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-violet-500 to-cyan-600 rounded-xl hover:from-violet-400 hover:to-cyan-500 transition-all shadow-xl shadow-violet-500/25"
        >
            {{ section.content.button_text || "Get Started" }}
        </button>
    </section>

    <!-- IMAGE -->
    <section
        v-else-if="section.type === 'image'"
        class="max-w-4xl mx-auto px-6 py-8"
    >
        <figure>
            <img
                :src="
                    section.content.url ||
                    'https://placehold.co/1200x600/1a1a2e/6366f1?text=Your+Image'
                "
                :alt="section.content.alt || 'Image'"
                class="w-full rounded-xl border border-white/[0.08]"
            />
            <figcaption
                v-if="section.content.caption"
                class="text-center text-xs text-gray-500 mt-2"
            >
                {{ section.content.caption }}
            </figcaption>
        </figure>
    </section>

    <!-- VIDEO -->
    <section
        v-else-if="section.type === 'video'"
        class="max-w-3xl mx-auto px-6 py-8"
    >
        <div
            class="aspect-video rounded-xl overflow-hidden border border-white/[0.08]"
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
                class="w-full h-full bg-white/[0.04] flex items-center justify-center text-gray-500"
            >
                Paste a YouTube or Vimeo URL
            </div>
        </div>
    </section>

    <!-- DIVIDER -->
    <section
        v-else-if="section.type === 'divider'"
        class="max-w-4xl mx-auto px-6 py-6"
    >
        <hr
            v-if="!section.content.style || section.content.style === 'line'"
            class="border-white/[0.08]"
        />
        <div
            v-else-if="section.content.style === 'dots'"
            class="flex justify-center gap-2"
        >
            <span class="w-1.5 h-1.5 rounded-full bg-gray-600" />
            <span class="w-1.5 h-1.5 rounded-full bg-gray-600" />
            <span class="w-1.5 h-1.5 rounded-full bg-gray-600" />
        </div>
        <div v-else class="h-8" />
    </section>
</template>
