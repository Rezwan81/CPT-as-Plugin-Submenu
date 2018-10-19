<?php 
/*
Plugin Name:CPT as Plugin Submenu
Author:Raihan Islam
Description:CPT as Plugin Submenu
 */

 add_action('admin_menu',function(){
    add_menu_page('Movies', 'Movies','manage_options','movies',false, 'dashicons-video-alt2');
    add_meta_box(
        'category-id',
       'Movie Category',
        'movie_category_call_fun',
        'movies',
        'side',
        'high'
    );
 });
function movie_category_call_fun($post){
    global $wpdb;
    // $category = $wpdb->get_results(
    //     $wpdb->prepare(
    //         "SELECT post_title from $wpdb->posts WHERE post_type = %s",
    //         'moviess'
    //     )
    //     );
    //     print_r($category);

    // $category= get_posts(array(
    //     'post_type'=> 'moviess',
    //     'post_status'=>'publish'
    // ));
    // print_r($category);

    ?>
        <label for="">Choose Category</label>
        <div>
            <?php
            $save_id = get_post_meta($post->ID, 'movie_category_select',true);
                wp_dropdown_pages( array(
                    'post_type'=> 'moviess',
                    'name'=>'movies_category',
                    'selected'=> $save_id

                ) );
            ?>
        </div>
    <?php
}
function category_save($post_id,$post){
    $name = $_REQUEST['movies_category'];
    update_post_meta($post_id,'movie_category_select', $name);
}
add_action('save_post','category_save',10,2);
add_action('init', 'codex_Movies_init');

/**
 * Register a Movies Category post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_Movies_init()
{
    $labels = array(
        'name' => _x('Movies ', 'post type general name', 'your-plugin-textdomain'),
        'singular_name' => _x('Movies ', 'post type singular name', 'your-plugin-textdomain'),
        'menu_name' => _x('Movies ', 'admin menu', 'your-plugin-textdomain'),
        'name_admin_bar' => _x('Movies ', 'add new on admin bar', 'your-plugin-textdomain'),
        'add_new' => _x('Add New', 'Movies ', 'your-plugin-textdomain'),
        'add_new_item' => __('Add New Movies ', 'your-plugin-textdomain'),
        'new_item' => __('New Movies ', 'your-plugin-textdomain'),
        'edit_item' => __('Edit Movies ', 'your-plugin-textdomain'),
        'view_item' => __('View Movies ', 'your-plugin-textdomain'),
        'all_items' => __('All Movies ', 'your-plugin-textdomain'),
        'search_items' => __('Search Movies ', 'your-plugin-textdomain'),
        'parent_item_colon' => __('Parent Movies :', 'your-plugin-textdomain'),
        'not_found' => __('No Movies  found.', 'your-plugin-textdomain'),
        'not_found_in_trash' => __('No Movies  found in Trash.', 'your-plugin-textdomain')
    );

    $args = array(
        'labels' => $labels,
        'description' => __('Description.', 'your-plugin-textdomain'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => 'movies',
        'query_var' => true,
        'rewrite' => array('slug' => 'Movies Category'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail')
    );

    register_post_type('movies', $args);
}

add_action('init', 'codex_Movies_Category_init');
/**
 * Register a Movies Category post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_Movies_Category_init()
{
    $labels = array(
        'name' => _x('Movies Category', 'post type general name', 'your-plugin-textdomain'),
        'singular_name' => _x('Movies Category', 'post type singular name', 'your-plugin-textdomain'),
        'menu_name' => _x('Movies Category', 'admin menu', 'your-plugin-textdomain'),
        'name_admin_bar' => _x('Movies Category', 'add new on admin bar', 'your-plugin-textdomain'),
        'add_new' => _x('Add New', 'Movies Category', 'your-plugin-textdomain'),
        'add_new_item' => __('Add New Movies Category', 'your-plugin-textdomain'),
        'new_item' => __('New Movies Category', 'your-plugin-textdomain'),
        'edit_item' => __('Edit Movies Category', 'your-plugin-textdomain'),
        'view_item' => __('View Movies Category', 'your-plugin-textdomain'),
        'all_items' => __('All Movies Category', 'your-plugin-textdomain'),
        'search_items' => __('Search Movies Category', 'your-plugin-textdomain'),
        'parent_item_colon' => __('Parent Movies Category:', 'your-plugin-textdomain'),
        'not_found' => __('No Movies Category found.', 'your-plugin-textdomain'),
        'not_found_in_trash' => __('No Movies Category found in Trash.', 'your-plugin-textdomain')
    );

    $args = array(
        'labels' => $labels,
        'description' => __('Description.', 'your-plugin-textdomain'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => 'movies',
        'query_var' => true,
        'rewrite' => array('slug' => 'Movies Category'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail')
    );

    register_post_type('moviess', $args);
}

add_action('restrict_manage_posts','category_filter');
function category_filter(){
    global $typenow;
    if($typenow == 'movies'){
        $category_id = $_GET['filter_by_category'];
        wp_dropdown_pages(array(
            'post_type' => 'moviess',
            'name'=>'filter_by_category',
            "show_option_none"=> 'Select Category',
            'selected'=> $category_id,
        ));
    }
   
}

add_filter('parse_query','parse_query_callback');
function parse_query_callback($query){
    global $typenow;
    global $pagenow;
    $category_id= $_GET['filter_by_category'];
    if($typenow == 'movies' && $pagenow == 'edit.php' && !empty($category_id)){
        $query->query_vars["meta_key"]= 'movie_category_select';
        $query->query_vars["meta_value"]= $category_id;
    }

}


?>