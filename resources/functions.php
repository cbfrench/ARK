<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.1', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7.1 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'assets' => require dirname(__DIR__).'/config/assets.php',
            'theme' => require dirname(__DIR__).'/config/theme.php',
            'view' => require dirname(__DIR__).'/config/view.php',
        ]);
    }, true);

/**
 * Allows for customization of the Site logo
 * 
 * Adds a customization area to the Customize section of the theme that 
 * allows the user to choose a Site logo instead of text.
 */
function m1_customize_register( $wp_customize ) {
    $wp_customize->add_setting( 'm1_logo' ); // Add setting for logo uploader
            
    // Add control for logo uploader (actual uploader)
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'm1_logo', array(
        'label'    => __( 'Upload Logo (replaces text)', 'm1' ),
        'section'  => 'title_tagline',
        'settings' => 'm1_logo',
    ) ) );
}
add_action( 'customize_register', 'm1_customize_register' );

/**
 * Adds ACF custom options page
 */
if(function_exists('acf_add_options_page')){
    acf_add_options_page(array(
        'page_title'    =>  'Theme General Settings',
        'menu_title'    =>  'Theme Settings',
        'menu_slug'     =>  'theme-general-settings',
        'capability'    =>  'edit_posts',
        'redirect'      =>  false
    ));
}

if($_REQUEST['health_reporting']){
    analyze_report();
}

/**
 * Gathers temperature data from students
 * 
 * Analyzes the data given from the collection form and
 * redirects the page based on matching information given
 * in the **`students`** database, checking temperature and symptoms
 * in the process.
 */
function analyze_report(){
    session_start();
    $child = [];
    $child['fname'] = $_POST['first_name'];
    $child['lname'] = $_POST['last_name'];
    $child['id'] = $_POST['id'];
    $child['temperature'] = $_POST['temperature'];
    $symptoms = 0;
    for($i = 1; $i <= 50; $i++){
        if(isset($_POST['symptom-' . $i])){
            $symptoms++;
        }
    }
    $child['symptoms'] = $symptoms;
    $_SESSION['child'] = $child;
    global $wpdb;
    $query = "SELECT * FROM students where id='{$child['id']}' and first_name='{$child['fname']}' and last_name='{$child['lname']}';";
    $results = $wpdb->get_results($query);
    if(count($results) == 0){
        echo '<div class="student-error"><div class="container"><div class="student-error__box"><p class="student-error__box__message">' . get_field("student_error_message", "option") . '</p><div class="student-error__box__close">+</div></div></div></div>';
    }
    else{
        if($child['temperature'] >= get_field('temperature', 'option') || $child['symptoms'] >= get_field('symptoms', 'option')['max_symptoms']){
            $now = new DateTime('now', new DateTimeZone('America/Los_Angeles'));
            $wpdb->update(
                'students',
                array(
                    'checked'           =>  $now->format('Y-m-d'),
                    'temperature'       =>  $child['temperature'],
                    'temperature_taken' =>  $now->format('Y-m-d H:i:s'),
                    'symptom_count'     =>  $child['symptoms'],
                ),
                array(
                    'id'                =>  (int)$child['id'],
                )
            );
            header('Location: /rejected');
            exit();
        }
        header('Location: /submitted');
        exit();
    }
}

/**
 * Generates the URL used for student verification
 * 
 * Takes the current date and the student information from the `$_SESSION`
 * variables and converts it into a URL suitable for passing information
 * that can be reobtained with a future `$_GET` request.
 * 
 * @return string
 */
function submitted_url(){
    session_start();
    $_SESSION['valid'] = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
    $qr_date = $_SESSION['valid']->format('M d, Y');
    $qr_day = $_SESSION['valid']->format('D');
    $site = get_site_url();
    $qr_content = $site . '/temperature-check?date=' . $qr_date . '&day=' . $qr_day . '&fname=' . $_SESSION['child']['fname'] . '&lname=' . $_SESSION['child']['lname'] . '&temp=' . $_SESSION['child']['temperature'];
    return $qr_content;
}

/**
 * Updates the given student in the database with illness information
 * 
 * Takes all of the data submitted to the homepage form and checks
 * the database for the child. If they exist, their information
 * is updated, including temperature and symptom count, as well as time
 * of check-in.
 */
function update_student($child){
    global $wpdb;
    $query = "SELECT * FROM students where id='{$child['id']}';";
    $results = $wpdb->get_results($query);
    if(count($results) == 0){
        echo 'error';
    }
    else{
        $now = new DateTime('now', new DateTimeZone('America/Los_Angeles'));
        $wpdb->update(
            'students',
            array(
                'checked'           =>  $now->format('Y-m-d'),
                'temperature'       =>  $child['temperature'],
                'temperature_taken' =>  $now->format('Y-m-d H:i:s'),
                'symptom_count'     =>  $child['symptoms'],
            ),
            array(
                'id'                =>  (int)$child['id'],
            )
        );
    }
}

/**
 * Gets the correct animal for the day for verificaiton.
 */
function verify_animal(){
    $date = new DateTime("now", new DateTimeZone("America/Los_Angeles"));
    srand((int)$date->format('Ymd'));
    $total_animals = get_field('animals', 'option');
    if(is_array($total_animals)) $total_animals = count($total_animals);
    $chosen = rand(1, $total_animals);
    while(have_rows('animals', 'option')) : the_row();
        if(get_row_index() == $chosen){
            return get_sub_field('animal');
        }
    endwhile;
    return;
}