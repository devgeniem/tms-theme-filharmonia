<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia\PostType;

use TMS\Theme\Base\Interfaces\PostType;

/**
 * Artist CPT
 *
 * @package TMS\Theme\Muumimuseo\PostType
 */
class Artist implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'artist';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the post type.
     *
     * @var string
     */
    private $url_slug = '';

    /**
     * Define the CPT description
     *
     * @var string
     */
    private $description = '';

    /**
     * This is used to position the post type menu in admin.
     *
     * @var int
     */
    private $menu_order = 5;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-buddicons-topics';

    /**
     * Constructor
     */
    public function __construct() {
        $this->url_slug    = 'artist';
        $this->description = _x( 'Artists', 'theme CPT', 'tms-theme-base' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
        add_filter(
            'tms/base/breadcrumbs/before_prepare',
            \Closure::fromCallable( [ $this, 'format_single_breadcrumbs' ] ),
            10,
            3
        );
    }

    /**
     * Get post type slug
     *
     * @return string
     */
    public function get_post_type() : string {
        return static::SLUG;
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Taiteilijat',
            'singular_name'         => 'Taiteilija',
            'menu_name'             => 'Taiteilijat',
            'name_admin_bar'        => 'Taiteilijat',
            'archives'              => 'Arkistot',
            'attributes'            => 'Ominaisuudet',
            'parent_item_colon'     => 'Vanhempi:',
            'all_items'             => 'Kaikki',
            'add_new_item'          => 'Lisää uusi',
            'add_new'               => 'Lisää uusi',
            'new_item'              => 'Uusi',
            'edit_item'             => 'Muokkaa',
            'update_item'           => 'Päivitä',
            'view_item'             => 'Näytä',
            'view_items'            => 'Näytä kaikki',
            'search_items'          => 'Etsi',
            'not_found'             => 'Ei löytynyt',
            'not_found_in_trash'    => 'Ei löytynyt roskakorista',
            'featured_image'        => 'Kuva',
            'set_featured_image'    => 'Aseta kuva',
            'remove_featured_image' => 'Poista kuva',
            'use_featured_image'    => 'Käytä kuvana',
            'insert_into_item'      => 'Aseta julkaisuun',
            'uploaded_to_this_item' => 'Lisätty tähän julkaisuun',
            'items_list'            => 'Listaus',
            'items_list_navigation' => 'Listauksen navigaatio',
            'filter_items_list'     => 'Suodata listaa',
        ];

        $rewrite = [
            'slug'       => static::SLUG,
            'with_front' => false,
            'pages'      => true,
        ];

        $args = [
            'label'           => $labels['name'],
            'description'     => '',
            'labels'          => $labels,
            'supports'        => [
                'title',
                'thumbnail',
            ],
            'hierarchical'    => false,
            'public'          => true,
            'menu_position'   => $this->menu_order,
            'menu_icon'       => $this->icon,
            'show_in_menu'    => true,
            'show_ui'         => true,
            'can_export'      => true,
            'has_archive'     => true,
            'rewrite'         => $rewrite,
            'show_in_rest'    => true,
            'capability_type' => 'artist',
            'map_meta_cap'    => true,
        ];

        register_post_type( static::SLUG, $args );
    }

    /**
     * Format single view breadcrumbs.
     *
     * @param array  $breadcrumbs  Default breadcrumbs.
     * @param string $current_type Post type.
     * @param string $current_id   Current post ID.
     *
     * @return array[]
     */
    public function format_single_breadcrumbs( $breadcrumbs, $current_type, $current_id ) {
        if ( $current_type !== self::SLUG ) {
            return $breadcrumbs;
        }

        return [
            'home' => [
                'title'     => _x( 'Home', 'Breadcrumbs', 'tms-theme-base' ),
                'permalink' => trailingslashit( get_home_url() ),
                'icon'      => '',
            ],
            [
                'title'     => _x( 'Orchestra', 'Breadcrumb text', 'tms-theme-base' ),
                'permalink' => get_post_type_archive_link( self::SLUG ),
                'icon'      => false,
            ],
            [
                'title'     => get_the_title( $current_id ),
                'permalink' => false,
                'icon'      => false,
                'is_active' => true,
            ],
        ];
    }

    /**
     * Get artist name
     *
     * @param int $post_id \WP_Post ID.
     *
     * @return string
     */
    public function get_artist_name( $post_id ) {
        return trim( get_field( 'first_name', $post_id ) . ' ' . get_field( 'last_name', $post_id ) );
    }
}