<?php
    // The Contact page holds the data
    $contact_page = get_page_by_path('contact');

    // Fetch the custom fields
    $email       = get_post_meta($contact_page->ID, '_happy_contact_email', true);
    $calendar    = get_post_meta($contact_page->ID, '_happy_contact_calendar', true);
    $twitter     = get_post_meta($contact_page->ID, '_happy_contact_twitter', true);
    $linkedin    = get_post_meta($contact_page->ID, '_happy_contact_linkedin', true);
    $reddit      = get_post_meta($contact_page->ID, '_happy_contact_reddit', true);

    // Your component classes passed from args
    $component_classes = $args['contact_modal_classes'] ??
        "bg-white rounded-xl shadow-2xl w-full max-w-xl transform transition-all overflow-hidden p-6 sm:p-8";
?>

<div class="<?php echo esc_attr($component_classes); ?>">
    <h2 class="text-gray12! text-lg! font-medium mb-2!" id="modal-title">Contact</h2>

    <p class="text-gray11! text-xs! mb-6">
        My local time:
        <span data-local-time
              class="text-xs! text-gray11! transition-opacity duration-200 ease-in-out opacity-0">
        </span>
    </p>

    <!-- Email Section -->
    <div class="space-y-4 border-b border-gray-200 py-4 flex flex-row justify-between">
        <div class='mb-0!'>
            <p class="text-sm font-medium! text-gray12">Email</p>
            <p class="text-gray11! text-xs!">Always happy to help</p>
        </div>

        <div class="flex flex-row justify-between border border-gray4 rounded-lg">
            <a href="mailto:<?php echo esc_attr($email); ?>"
               class="flex-1 w-full justify-center flex items-center border border-r-gray4! border-transparent px-2.5 py-2 text-xs! hover:bg-gray4">

               <span class="iconify text-sm mr-1 text-gray10!" data-icon="quill:compose" data-height="18" data-width="18"></span>

                <span class="ml-0.5 text-xs text-gray12! font-medium">Compose</span>
            </a>

            <a id="copy-email"
               type="button"
               data-email="<?php echo esc_attr($email); ?>"
               class="flex-1 shrink-0 flex items-center px-2.5 py-2 text-xs! cursor-pointer hover:bg-gray4">

                <span class="iconify text-sm mr-1 text-gray10!" data-icon="fluent:document-copy-16-regular" data-height="18" data-width="18"></span>

                <span class="ml-0.5 text-xs font-medium text-gray12!">Copy</span>
            </a>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="space-y-4 border-b border-gray-200 py-6 flex flex-row justify-between items-center">
        <div class="flex flex-col justify-between mb-0!">
            <p class="text-sm font-medium! text-gray12 mb-0!">Arrange a call</p>
            <p class="text-gray11! text-xs!">Chat with me on a call</p>
        </div>

        <a href="<?php echo esc_url($calendar); ?>"
           target="_blank"
           class="shrink-0 font-medium rounded-sm px-2.5 py-2 text-xs! hover:bg-gray4 border border-gray4! text-gray12!">
            Calendar
        </a>
    </div>

    <div class="space-y-4 py-6 flex flex-row justify-between items-center">
    <div class='mb-0!'>
        <p class="text-sm font-medium! text-gray12">Stay in touch</p>
        <p class="text-gray11! text-xs!">I'm most responsive on LinkedIn</p>
    </div>

    <div class="flex flex-row justify-between items-center">
            <!-- Twitter - using the 'logos' collection for colored icon -->
            <a href="<?php echo esc_url($twitter); ?>"
            target="_blank"
            class="text-gray12! flex flex-row justify-between items-center shrink-0 font-medium rounded-sm px-2.5 py-2 text-[11px]! hover:bg-gray4">
                <span class="iconify text-sm mr-1" data-icon="logos:twitter"></span>
                Twitter
            </a>

            <!-- LinkedIn - using the 'logos' collection for colored icon -->
            <a href="<?php echo esc_url($linkedin); ?>"
            target="_blank"
            class="text-gray12! flex flex-row justify-between items-center shrink-0 font-medium rounded-sm px-2.5 py-2 text-[11px]! hover:bg-gray4">
                <span class="iconify text-sm mr-1" data-icon="logos:linkedin-icon"></span>
                LinkedIn
            </a>

            <!-- Reddit - using the 'logos' collection for colored icon -->
            <a href="<?php echo esc_url($reddit); ?>"
            target="_blank"
            class="text-gray12! flex flex-row justify-between items-center shrink-0 font-medium rounded-sm px-2.5 py-2 text-[11px]! hover:bg-gray4">
                <span class="iconify text-sm mr-1" data-icon="logos:reddit-icon"></span>
                Reddit
            </a>
        </div>
    </div>

</div>
