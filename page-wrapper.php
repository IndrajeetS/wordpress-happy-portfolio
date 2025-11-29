<?php get_header(); ?>

<div id="app" class="relative flex h-screen m-0! overflow-hidden text-gray-800 font-inter bg-gray2">

    <?php get_template_part('template-parts/components/app', 'navigation'); ?>

    <main id="content" class="m-2 md:my-2 md:mr-2 md:ml-62 flex flex-col items-center h-[calc(100vh-1rem)] relative rounded-lg p-4 md:p-8 order-1 grow bg-white overflow-auto transition duration-200 ease-in-out shadow-md">

        <?php
        // Load content-{slug}.php dynamically
        if (!empty($content_template)) {
            get_template_part('template-parts/pages/content', $content_template);
        } else {
            echo "<p>No template specified.</p>";
        }
        ?>

    </main>

</div>

<?php get_footer(); ?>
