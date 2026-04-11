<?php
/**
 * The template part for displaying 404 (Not Found) content.
 *
 * @package HappyPortfolio
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">

    <!-- Large 404 Background Text -->
    <div class="relative mb-5 flex flex-col items-center justify-center">

        <!-- Background 404 -->
        <h3 class="text-6xl md:text-8xl font-bold leading-none
               select-none opacity-5 dark:opacity-10
               text-gray12 pointer-events-none">
            404
        </h3>

        <!-- Foreground Text -->
        <h1 class="relative text-3xl md:text-5xl font-bold text-gray12 tracking-tight">
            Page Not Found
        </h1>

    </div>

    <!-- Message -->
    <p class="text-gray11 text-lg mb-10 max-w-md mx-auto leading-relaxed">
        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>

    <!-- Visual Element (Optional - Simple Circle) -->
    <div class="relative w-24 h-24 mb-10">

        <!-- Glow layer 1 -->
        <div class="absolute inset-0 rounded-full
                bg-(--color-glow-primary)
                animate-[blobFloat_6s_ease-in-out_infinite,glowPulse_4s_ease-in-out_infinite]">
        </div>

        <!-- Glow layer 2 -->
        <div class="absolute inset-0 rounded-full
                bg-(--color-glow-secondary)
                animate-[blobFloatAlt_7s_ease-in-out_infinite,glowPulse_5s_ease-in-out_infinite]">
        </div>

        <!-- Center glass -->
        <div class="relative flex items-center justify-center w-full h-full
                border border-(--color-border)
                bg-(--color-contentBg)/40
                backdrop-blur-xl
                rounded-full shadow-inner">

            <svg class="w-10 h-10 text-(--color-gray11)
                    animate-[iconFloat_3s_ease-in-out_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

        </div>

    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 items-center">

        <!-- Primary -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-item flex items-center gap-2
               px-5 py-2.5
               rounded-md
               text-sm font-medium
               transition-colors duration-150" data-page="home" data-href="<?php echo esc_url(home_url('/')); ?>">

            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>

            Back to Home
        </a>

        <!-- Secondary -->
        <button onclick="window.history.back()" class="px-5 py-2.5
               rounded-md
               border border-(--color-border)
               text-(--color-gray11)
               text-sm font-medium
               transition-colors duration-150
               hover:bg-(--color-nav-hover)">
            Go Back
        </button>

    </div>

</div>