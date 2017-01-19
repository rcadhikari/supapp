<?php

// Declaration of theme supported features

/* General Print Function */
if (!function_exists('pm')) {
    function pm($var, $exit = 0, $new_line = 0) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';

        if ($new_line) echo '<br/>';;

        if ($exit) exit();
    }
}

function wp_header_description()
{
    echo get_bloginfo('description');
}

function the_widget_header_text()
{
    $sidebars_widgets = wp_get_sidebars_widgets();
    if ( ! is_active_sidebar( 'HeaderText' ) ) { return; }
?>
    <div id="navbar-header-text" class="widget-area" role="complementary">
        <?php dynamic_sidebar( 'HeaderText' ); ?>
    </div><!-- #secondary -->
<?php
}

// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
    global $post;
    return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read more...</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function wp_basic_bootstrap_theme_support()
{
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption'
    ));
    add_theme_support('post-thumbnails');      // wp thumbnails (sizes handled in functions.php)
    set_post_thumbnail_size(125, 125, true);   // default thumb size
    add_theme_support('automatic-feed-links'); // rss thingy
    add_theme_support('custom-background', array(
        'default-color' => '#595959',
        'default-image' => get_template_directory_uri() . '/images/custom-background.jpg',
        'default-repeat' => 'repeat',
        'default-position-x' => 'center',
        'default-attachment' => 'fixed',
    ));

    add_theme_support( 'custom-header', [
        'width'         => 980,
        'height'        => 110,
        'default-image' => get_template_directory_uri() . '/images/header.jpg',
    ]);

    add_theme_support('title-tag');
    register_nav_menus(                      // wp3+ menus
        array(
            'main_nav' => __('Main Menu', 'wp-basic-bootstrap'),   // main nav in header
        )
    );
    add_image_size('wp_basic_bootstrap_featured', 1140, 1140 * (9 / 21), true);
}

add_action('after_setup_theme', 'wp_basic_bootstrap_theme_support');

function wp_basic_bootstrap_theme_scripts()
{
    // For child themes
    wp_register_style('wpbs-style', get_stylesheet_directory_uri() . '/style.css', array(), null, 'all');
    wp_enqueue_style('wpbs-style');
    wp_register_script('bower-libs',
        get_template_directory_uri() . '/app.min.js',
        array('jquery'),
        null);
    wp_enqueue_script('bower-libs');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'wp_basic_bootstrap_theme_scripts');

function wp_basic_bootstrap_load_fonts()
{
    wp_register_style('wp_basic_bootstrap_googleFonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700');
    wp_enqueue_style('wp_basic_bootstrap_googleFonts');
}

add_action('wp_print_styles', 'wp_basic_bootstrap_load_fonts');

// Set content width
if (!isset($content_width))
    $content_width = 750;

// Sidebar and Footer declaration
function wp_basic_bootstrap_register_sidebars()
{
    register_sidebar(array(
        'id' => 'sidebar-right',
        'name' => __('Right Sidebar', 'wp-basic-bootstrap'),
        'description' => __('Used on every page.', 'wp-basic-bootstrap'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'id' => 'footer1',
        'name' => __('Footer', 'wp-basic-bootstrap'),
        'before_widget' => '<div id="%1$s" class="widget col-xs-6 col-sm-4 col-md-3 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

}

add_action('widgets_init', 'wp_basic_bootstrap_register_sidebars');

// Menu output mods
class wp_basic_bootstrap_Bootstrap_walker extends Walker_Nav_Menu
{

    function start_el(&$output, $object, $depth = 0, $args = Array(), $current_object_id = 0)
    {

        global $wp_query;
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $dropdown = $args->has_children && $depth == 0;

        $class_names = $value = '';

        // If the item has children, add the dropdown class for bootstrap
        if ($dropdown) {
            $class_names = "dropdown ";
        }

        $classes = empty($object->classes) ? array() : (array)$object->classes;

        $class_names .= join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $object));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $output .= $indent . '<li id="menu-it       em-' . $object->ID . '"' . $value . $class_names . '>';

        if ($dropdown) {
            $attributes = ' href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"';
        } else {
            $attributes = !empty($object->attr_title) ? ' title="' . esc_attr($object->attr_title) . '"' : '';
            $attributes .= !empty($object->target) ? ' target="' . esc_attr($object->target) . '"' : '';
            $attributes .= !empty($object->xfn) ? ' rel="' . esc_attr($object->xfn) . '"' : '';
            $attributes .= !empty($object->url) ? ' href="' . esc_attr($object->url) . '"' : '';
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $object->title, $object->ID);
        $item_output .= $args->link_after;

        // if the item has children add the caret just before closing the anchor tag
        if ($dropdown) {
            $item_output .= ' <b class="caret"></b>';
        }
        $item_output .= '</a>';

        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $object, $depth, $args);
    } // end start_el function

    function start_lvl(&$output, $depth = 0, $args = Array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='dropdown-menu' role='menu'>\n";
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        $id_field = $this->db_fields['id'];
        if (is_object($args[0])) {
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);
        }
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

// Add Twitter Bootstrap's standard 'active' class name to the active nav link item
function wp_basic_bootstrap_add_active_class($classes, $item)
{
    if (in_array('current-menu-item', $classes)) {
        $classes[] = "active";
    }

    return $classes;
}

add_filter('nav_menu_css_class', 'wp_basic_bootstrap_add_active_class', 10, 2);

// display the main menu bootstrap-style
// this menu is limited to 2 levels (that's a bootstrap limitation)
function wp_basic_bootstrap_display_main_menu()
{
    wp_nav_menu(
        array(
            'theme_location' => 'main_nav', /* where in the theme it's assigned */
            'menu' => 'main_nav', /* menu name */
            'menu_class' => 'nav navbar-nav navbar-right',
            'container' => false, /* container class */
            'depth' => 2,
            'walker' => new wp_basic_bootstrap_Bootstrap_walker(),
        )
    );
}

/*
  A function used in multiple places to generate the metadata of a post.
*/
function wp_basic_bootstrap_display_post_meta()
{
    global $post;
    $post_id = $post->id;
    ?>
    <ul>
        <li><span>Posted by</span> <?php echo ucfirst( get_the_author() ); ?></li>
        <li>Category: <?php the_category(', ', 'multiple', $post_id ); ?></li>
        <?php edit_post_link(__('Edit', "wp-basic-bootstrap"), '<li><span class="glyphicon glyphicon-pencil"></span> ', '</li>'); ?>
    </ul>
    <p class="tag"><?php the_tags(null, ' '); ?></p>
    <?php
}

// display the main menu bootstrap-style
function wp_basic_bootstrap_page_navi()
{
    global $wp_query;
    ?>
    <?php if (get_next_posts_link() || get_previous_posts_link()) { ?>
    <nav class="block">
        <ul class="pager pager-unspaced">
            <li class="previous"><?php next_posts_link("&laquo; " . __('Older posts', "wp-basic-bootstrap")); ?></li>
            <li class="next"><?php previous_posts_link(__('Newer posts', "wp-basic-bootstrap") . " &rsquo;"); ?></li>
        </ul>
    </nav>
<?php } ?>

    <?php
}

if ( ! function_exists( 'show_post_datetime' ) ) :
    /**
     * Gets a nicely formatted string for the published date.
     */
    function show_post_datetime() {
        global $params;
        $my_date = the_date('j,F,Y', '', '', '');
        $params = explode(',', $my_date);
        ?>
        <div class="post-date">
            <div class="day"><?php global $params;echo $params[0]; ?></div>
            <div class="date">
                <div class="month"><?php global $params;echo $params[1]; ?></div>
                <div class="year"><?php global $params;echo $params[2]; ?></div>
            </div>
        </div>
        <?php
    }
endif;

function wp_basic_bootstrap_display_post_homepage($multiple_on_page)
{ ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class("block"); ?> role="article">

        <header class="row">
            <div class="featured-image col-xs-12 col-sm-4"> <?php the_post_thumbnail('wp_basic_bootstrap_featured'); ?> </div>

            <div class="article-head col-xs-12 col-sm-8">
                <?php if ($multiple_on_page) : ?>
                    <div class="article-header">
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><br>
                        <?php show_post_datetime();?>
                    </div>
                <?php else: ?>
                    <div class="article-header">
                        <h1><?php the_title(); ?></h1>
                    </div>
                <?php endif ?>

                <div class="tags">
                    <?php wp_basic_bootstrap_display_post_meta() ?>
                </div>

            </div>

        </header>

        <section class="post_content">
            <?php
            if ($multiple_on_page) {
                the_excerpt();
            } else {
                echo "content start here";
                the_content();
                echo "content end here";
                wp_link_pages();
            }
            ?>
        </section>

    </article>

    <?php
}

function wp_basic_bootstrap_display_post($multiple_on_page)
{ ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class("block"); ?> role="article">

        <header class="row">
            <div class="featured-image col-xs-12 col-sm-4"> <?php the_post_thumbnail('wp_basic_bootstrap_featured'); ?> </div>

            <div class="article-head col-xs-12 col-sm-8">

                <div class="article-header">
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><br>
                    <?php show_post_datetime();?>
                </div>

                <div class="tags">
                    <?php wp_basic_bootstrap_display_post_meta() ?>
                </div>

            </div>

        </header>

        <section class="post_content">
            <?php
            if ($multiple_on_page) {
                the_excerpt();
            } else {
                the_content();
                wp_link_pages();
            }
            ?>
        </section>

    </article>

<?php }

function wp_basic_bootstrap_display_content_page($multiple_on_page)
{ ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class("block"); ?> role="article">
        <header>
            <div class="article-header">
                <div class="title"><?php the_title(); ?></div>
            </div>
        </header>

        <section class="post_content">
            <?php
            if ($multiple_on_page) {
                the_excerpt();
            } else {
                the_content();
                wp_link_pages();
            }
            ?>
        </section>

    </article>

<?php }

function wp_basic_bootstrap_main_classes()
{
    $nbr_sidebars = (is_active_sidebar('sidebar-left') ? 1 : 0) + (is_active_sidebar('sidebar-right') ? 1 : 0);
    $classes = "";
    if ($nbr_sidebars == 0) {
        $classes .= "col-sm-8 col-md-push-2";
    } else if ($nbr_sidebars == 1) {
        $classes .= "col-md-8";
    } else {
        $classes .= "col-md-6";
    }
    if (is_active_sidebar('sidebar-left')) {
        $classes .= " col-md-push-" . ($nbr_sidebars == 2 ? 3 : 4);
    }
    echo $classes;
}

function wp_basic_bootstrap_sidebar_right_classes()
{
    $nbr_sidebars = (is_active_sidebar('sidebar-left') ? 1 : 0) + (is_active_sidebar('sidebar-right') ? 1 : 0);
    echo 'col-md-' . ($nbr_sidebars == 2 ? 3 : 4);
}
