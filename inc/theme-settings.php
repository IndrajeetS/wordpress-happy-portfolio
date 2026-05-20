<?php
/**
 * Theme Settings Page
 */
if (!defined('ABSPATH')) exit;

function happy_portfolio_add_theme_settings_page() {
    add_theme_page(
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'happy-theme-settings',
        'happy_portfolio_theme_settings_html'
    );
}
add_action('admin_menu', 'happy_portfolio_add_theme_settings_page');

function happy_portfolio_register_settings() {
    // Greetings Settings
    register_setting('happy_theme_options_group', 'happy_greeting_morning');
    register_setting('happy_theme_options_group', 'happy_greeting_afternoon');
    register_setting('happy_theme_options_group', 'happy_greeting_evening');
    register_setting('happy_theme_options_group', 'happy_greeting_night');
    
    // Navigation Title
    register_setting('happy_theme_options_group', 'happy_nav_title');
}
add_action('admin_init', 'happy_portfolio_register_settings');

function happy_portfolio_theme_settings_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Happy Portfolio Theme Settings</h1>
        <form action="options.php" method="post" style="max-width: 600px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px;">
            <?php
            settings_fields('happy_theme_options_group');
            do_settings_sections('happy_theme_options_group');
            ?>
            
            <h2 style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">App Navigation</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">Navigation Title</th>
                    <td>
                        <input type="text" name="happy_nav_title" value="<?php echo esc_attr(get_option('happy_nav_title', 'Happy')); ?>" class="regular-text" />
                        <p class="description">Replaces the hardcoded "Happy" text in the sidebar navigation.</p>
                    </td>
                </tr>
            </table>

            <h2 style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; margin-top: 40px;">Time-Based Greetings</h2>
            <p>Set the greetings displayed on the home page depending on the time of day.</p>
            <table class="form-table">
                <tr>
                    <th scope="row">Morning Greeting (5 AM - 12 PM)</th>
                    <td>
                        <input type="text" name="happy_greeting_morning" value="<?php echo esc_attr(get_option('happy_greeting_morning', 'Good morning')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Afternoon Greeting (12 PM - 5 PM)</th>
                    <td>
                        <input type="text" name="happy_greeting_afternoon" value="<?php echo esc_attr(get_option('happy_greeting_afternoon', 'Good afternoon')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Evening Greeting (5 PM - 11 PM)</th>
                    <td>
                        <input type="text" name="happy_greeting_evening" value="<?php echo esc_attr(get_option('happy_greeting_evening', 'Good evening')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Night Greeting (11 PM - 5 AM)</th>
                    <td>
                        <input type="text" name="happy_greeting_night" value="<?php echo esc_attr(get_option('happy_greeting_night', 'In dreamland. Do not disturb. 😴')); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
