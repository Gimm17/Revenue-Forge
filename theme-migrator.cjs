const fs = require("fs");

const filePaths = [
    "resources/js/Pages/Offers/Create.vue",
    "resources/js/Pages/Offers/Edit.vue",
    "resources/js/Pages/Offers/LandingPageBuilder.vue",
    "resources/js/Pages/Customers/Index.vue",
    "resources/js/Pages/Customers/Show.vue",
    "resources/js/Pages/Affiliates/Index.vue",
    "resources/js/Pages/Affiliates/Show.vue",
    "resources/js/Pages/Broadcasts/Index.vue",
    "resources/js/Pages/Webhooks/Index.vue",
    "resources/js/Pages/Billing/Index.vue",
    "resources/js/Pages/Settings/WorkspaceSettings.vue",
];

const replacements = [
    {
        target: /bg-white\/\[0\.04\] border border-white\/\[0\.08\]/g,
        replacement:
            "bg-white border border-gray-100 shadow-sm ring-1 ring-black/5",
    },
    {
        target: /bg-white\/\[0\.02\] border border-white\/\[0\.04\]/g,
        replacement: "bg-gray-50 border border-gray-100 rounded-xl",
    },
    {
        target: /bg-white\/\[0\.06\] border border-white\/\[0\.1\]/g,
        replacement: "bg-white border border-gray-200 placeholder-gray-400",
    },
    {
        target: /bg-white\/\[0\.06\] border border-white\/\[0\.08\]/g,
        replacement: "bg-white border border-gray-200 placeholder-gray-400",
    },
    { target: /bg-white\/\[0\.08\]/g, replacement: "bg-gray-100" },
    { target: /text-gray-300/g, replacement: "text-gray-900" },
    { target: /text-gray-400/g, replacement: "text-gray-500" },
    { target: /text-white/g, replacement: "text-gray-900" },
    { target: /border-cyan-500\/50/g, replacement: "border-[#00D261]/50" },
    { target: /ring-cyan-500\/30/g, replacement: "ring-[#00D261]/30" },
    { target: /text-cyan-400/g, replacement: "text-[#00D261]" },
    { target: /text-cyan-300/g, replacement: "text-[#00D261]" },
    { target: /text-cyan-500/g, replacement: "text-[#00D261]" },
    {
        target: /from-cyan-500 to-violet-600/g,
        replacement: "from-[#25f47b] to-[#00D261]",
    },
    {
        target: /from-cyan-400 to-violet-500/g,
        replacement: "from-[#25f47b] to-[#00D261]",
    },
    { target: /shadow-cyan-500\/20/g, replacement: "shadow-[#25f47b]/30" },
    {
        target: /hover:from-cyan-400 hover:to-violet-500/g,
        replacement: "hover:scale-105",
    },
    {
        target: /bg-gradient-to-br from-cyan-500\/\[0\.06\] to-violet-500\/\[0\.06\] border border-cyan-500\/20/g,
        replacement:
            "bg-gradient-to-br from-[#25f47b]/5 to-[#00D261]/5 border border-[#00D261]/20 shadow-sm",
    },
    {
        target: /text-xl font-bold text-gray-900/g,
        replacement:
            "text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl",
    },
    { target: /bg-\[\#13151d\]/g, replacement: "bg-white" },
];

for (const path of filePaths) {
    if (!fs.existsSync(path)) {
        console.log(`Skipping ${path}, file not found.`);
        continue;
    }
    let content = fs.readFileSync(path, "utf8");

    // specifically handle the header text-white -> text-gray-900 first to avoid conflict and make it larger
    content = content.replace(
        /<h1 class="text-xl font-bold text-white">/g,
        '<h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">',
    );

    for (const { target, replacement } of replacements) {
        content = content.replace(target, replacement);
    }

    fs.writeFileSync(path, content, "utf8");
    console.log(`Updated ${path}`);
}
