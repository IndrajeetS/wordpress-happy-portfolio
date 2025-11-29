<div id="contact-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-overlayBg bg-opacity-75 transition-opacity duration-300"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <?php
        // Define the classes to be passed in an array
        $modal_args = array(
            'contact_modal_classes' => "bg-white rounded-xl shadow-2xl w-full max-w-md md:max-w-xl transform transition-all overflow-hidden p-6! sm:px-6"
        );

        // Pass the arguments array to the template part
        get_template_part('template-parts/content', 'contact-model-content', $modal_args);
    ?>
</div>
