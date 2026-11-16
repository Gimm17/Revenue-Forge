const fs = require("fs");

const filePaths = [
    "resources/js/Layouts/PublicLayout.vue",
    "resources/js/Layouts/GuestLayout.vue",
    "resources/js/Pages/Public/Landing.vue",
    "resources/js/Pages/Public/Pricing.vue",
    "resources/js/Pages/Public/Offer.vue",
    "resources/js/Pages/Auth/Login.vue",
    "resources/js/Pages/Auth/Register.vue",
    "resources/js/Pages/Auth/ForgotPassword.vue",
    "resources/js/Pages/Auth/ResetPassword.vue",
    "resources/js/Pages/Auth/ConfirmPassword.vue",
    "resources/js/Pages/Auth/VerifyEmail.vue",
];

const replacements = [
    // Backgrounds
    { target: /bg-\[\#0a0b10\]/g, replacement: "bg-white" },
    { target: /bg-gray-950/g, replacement: "bg-white" },
    { target: /bg-\[\#0d0e14\]/g, replacement: "bg-white" },
    { target: /bg-\[\#13151d\]/g, replacement: "bg-white" },

    // Cards
    {
        target: /bg-white\/\[0\.04\] border border-white\/\[0\.08\]/g,
        replacement:
            "bg-white border border-gray-100 shadow-xl ring-1 ring-black/5",
    },
    {
        target: /bg-white\/\[0\.02\] border border-white\/\[0\.04\]/g,
        replacement: "bg-white border border-gray-100 shadow-sm rounded-xl",
    },
    {
        target: /bg-white\/\[0\.06\] border border-white\/\[0\.1\]/g,
        replacement: "bg-white border border-gray-200 placeholder-gray-400",
    },
    { target: /bg-white\/\[0\.06\]/g, replacement: "bg-gray-50" },

    // Borders
    { target: /border-white\/\[0\.06\]/g, replacement: "border-gray-100" },
    { target: /border-white\/\[0\.08\]/g, replacement: "border-gray-100" },
    { target: /border-white\/\[0\.1\]/g, replacement: "border-gray-200" },
    { target: /ring-white\/\[0\.05\]/g, replacement: "ring-gray-200" },

    // Text colors
    { target: /text-gray-300/g, replacement: "text-gray-900" },
    { target: /text-gray-400/g, replacement: "text-gray-500" },
    { target: /text-gray-500/g, replacement: "text-gray-500" },
    { target: /text-white/g, replacement: "text-gray-900" },

    // Primary colors
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
    {
        target: /from-cyan-500 to-cyan-400/g,
        replacement: "from-[#25f47b] to-[#00D261]",
    },
    { target: /shadow-cyan-500\/20/g, replacement: "shadow-[#25f47b]/30" },
    { target: /shadow-cyan-500\/25/g, replacement: "shadow-[#25f47b]/30" },
    { target: /shadow-cyan-500\/40/g, replacement: "shadow-[#25f47b]/40" },

    // Gradients & Hovers
    {
        target: /hover:from-cyan-400 hover:to-violet-500/g,
        replacement: "hover:scale-105",
    },
    {
        target: /bg-gradient-to-br from-cyan-500\/\[0\.06\] to-violet-500\/\[0\.06\]/g,
        replacement: "bg-gradient-to-br from-[#25f47b]/5 to-[#00D261]/5",
    },

    // Fix typography
    {
        target: /text-xl font-bold text-gray-900/g,
        replacement:
            "text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl",
    },
    {
        target: /text-3xl font-bold text-gray-900/g,
        replacement:
            "text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl",
    },
    {
        target: /text-4xl font-bold text-gray-900/g,
        replacement:
            "text-5xl font-extrabold tracking-tight text-gray-900 sm:text-6xl",
    },
];

for (const path of filePaths) {
    if (!fs.existsSync(path)) {
        console.log(`Skipping ${path}, file not found.`);
        continue;
    }
    let content = fs.readFileSync(path, "utf8");

    // replace `text-white` on primary buttons
    content = content.replace(
        /text-gray-900 bg-gradient-to-r from-\[\#25f47b\] to-\[\#00D261\]/g,
        "text-white bg-gradient-to-r from-[#25f47b] to-[#00D261]",
    );

    for (const { target, replacement } of replacements) {
        content = content.replace(target, replacement);
    }

    // Post-process to fix primary buttons text color back to white
    // since we changed text-white -> text-gray-900
    content = content.replace(
        /text-gray-900 bg-gradient-to-r from-\[\#25f47b\] to-\[\#00D261\]/g,
        "text-white bg-gradient-to-r from-[#25f47b] to-[#00D261]",
    );
    content = content.replace(
        /text-gray-900 bg-\[\#00D261\]/g,
        "text-white bg-[#00D261]",
    );
    content = content.replace(
        /text-gray-900 bg-[#00D261]/g,
        "text-white bg-[#00D261]",
    );
    content = content.replace(
        /text-gray-900 font-bold bg-[#00D261]/g,
        "text-white font-bold bg-[#00D261]",
    );

    fs.writeFileSync(path, content, "utf8");
    console.log(`Updated ${path}`);
}
