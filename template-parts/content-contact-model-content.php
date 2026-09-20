<?php
$contact_page = get_page_by_path('contact');

// ✅ SAFETY CHECK
if (!$contact_page || !isset($contact_page->ID)) {
    return; // Stop rendering component completely
}

$contact_id = $contact_page->ID;

// Fetch safely
$email = get_post_meta($contact_id, '_happy_contact_email', true);
$calendar = get_post_meta($contact_id, '_happy_contact_calendar', true);
$twitter = get_post_meta($contact_id, '_happy_contact_twitter', true);
$linkedin = get_post_meta($contact_id, '_happy_contact_linkedin', true);
$reddit = get_post_meta($contact_id, '_happy_contact_reddit', true);
$instagram = get_post_meta($contact_id, '_happy_contact_instagram', true);
$facebook = get_post_meta($contact_id, '_happy_contact_facebook', true);
$github = get_post_meta($contact_id, '_happy_contact_github', true);

$social_links = [
    'Twitter' => ['url' => $twitter, 'icon' => 'logos:twitter'],
    'LinkedIn' => ['url' => $linkedin, 'icon' => 'logos:linkedin-icon'],
    'GitHub' => ['url' => $github, 'icon' => 'logos:github-icon'],
    'Instagram' => ['url' => $instagram, 'icon' => 'logos:instagram-icon'],
    'Facebook' => ['url' => $facebook, 'icon' => 'logos:facebook'],
    'Reddit' => ['url' => $reddit, 'icon' => 'logos:reddit-icon'],
];

// Your component classes passed from args
$component_classes = $args['contact_modal_classes'] ??
    "bg-white rounded-xl shadow-2xl w-full max-w-xl transform transition-all overflow-hidden p-6 sm:p-8";
?>

<div class="<?php echo esc_attr($component_classes); ?>">
    <h2 id="modal-title" class="font-heading text-base font-medium text-gray12 mb-2">Contact</h2>

    <p class="text-gray11 text-xxs! font-bold uppercase tracking-[0.03em] mb-6">
        My local time:
        <span data-local-time class="opacity-0 transition-opacity duration-200 ease-in-out"></span>
    </p>

    <!-- Email Section -->
    <div class="space-y-4 border-b border-border py-4 flex flex-row justify-between">
        <div class="m-0">
            <p class="text-sm! font-medium text-gray12">Email</p>
            <p class="text-gray11 text-xs!">Always happy to help</p>
        </div>

        <div class="flex flex-row justify-between border border-gray4 rounded-lg">
            <a href="mailto:<?php echo esc_attr($email); ?>"
                class="flex-1 w-full justify-center flex items-center border border-r-gray4 border-transparent px-2.5 py-2 text-xs hover:bg-gray4">

                <span class="iconify text-sm mr-1 text-gray10!" data-icon="quill:compose" data-height="18"
                    data-width="18"></span>

                <span class="ml-0.5 text-xs text-gray12 font-medium">Compose</span>
            </a>

            <a id="copy-email" data-email="<?php echo esc_attr($email); ?>"
                class="flex-1 shrink-0 flex items-center px-2.5 py-2 text-xs cursor-pointer hover:bg-gray4">

                <span class="iconify text-sm mr-1 text-gray10!" data-icon="fluent:document-copy-16-regular"
                    data-height="18" data-width="18"></span>

                <span class="ml-0.5 text-xs font-medium text-gray12!">Copy</span>
            </a>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="space-y-4 border-b border-border py-6 flex flex-row justify-between items-center">
        <div class="m-0">
            <p class="text-sm! font-medium text-gray12">Arrange a call</p>
            <p class="text-gray11 text-xs!">Chat with me on a call</p>
        </div>

        <?php if (!empty($calendar)): ?>
            <a href="<?php echo esc_url($calendar); ?>" target="_blank" rel="noopener noreferrer"
                class="shrink-0 font-medium rounded-sm px-2.5 py-2 text-xs hover:bg-gray4 border border-gray4 text-gray12!">
                Calendar
            </a>
        <?php endif; ?>
    </div>

    <div class="space-y-4 py-6 flex flex-row justify-between items-center">
        <div class="m-0">
            <p class="text-sm! font-medium text-gray12">Stay in touch</p>
            <p class="text-gray11 text-xs!">I'm most responsive on LinkedIn</p>
        </div>

        <div class="flex flex-row flex-wrap justify-end items-center gap-y-1 social-links">
            <?php foreach ($social_links as $label => $social_link): ?>
                <?php if (!empty($social_link['url'])): ?>
                    <a href="<?php echo esc_url($social_link['url']); ?>" target="_blank" rel="noopener noreferrer"
                        class="text-gray12! flex flex-row justify-between items-center shrink-0 font-medium rounded-sm px-2.5 py-2 text-[11px] hover:bg-gray4">
                        <span class="iconify text-sm mr-1" data-icon="<?php echo esc_attr($social_link['icon']); ?>"></span>
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</div>