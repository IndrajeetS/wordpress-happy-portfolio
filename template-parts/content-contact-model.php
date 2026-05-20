<div id="contact-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-overlayBg/75 transition-opacity duration-300"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <?php
    $modal_args = array(
        'contact_modal_classes' => "w-full max-w-md md:max-w-xl transform overflow-hidden rounded-xl bg-white p-6 shadow-2xl sm:px-6 transition-all"
    );

    get_template_part('template-parts/content', 'contact-model-content', $modal_args);
    ?>
</div>