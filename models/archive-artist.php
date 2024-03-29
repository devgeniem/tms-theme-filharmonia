<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Filharmonia\PostType\Artist;
use TMS\Theme\Filharmonia\Taxonomy\ArtistCategory;

/**
 * Archive for Artist CPT
 */
class ArchiveArtist extends BaseModel {

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'artist-search';

    /**
     * Artist category filter name.
     */
    const FILTER_QUERY_VAR = 'artist-filter';

    /**
     * Hooks
     */
    public static function hooks() : void {
        add_action(
            'pre_get_posts',
            [ __CLASS__, 'modify_query' ]
        );
    }

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get filter query var value
     *
     * @return int|null
     */
    protected static function get_filter_query_var() {
        $value = get_query_var( self::FILTER_QUERY_VAR, false );

        return ! $value
            ? null
            : intval( $value );
    }

    /**
     * Page title
     *
     * @return string
     */
    public function page_title() : string {
        return _x( 'Orchestra', 'Archive Title', 'tms-theme-filharmonia' );
    }

    /**
     * Return translated strings.
     *
     * @return array[]
     */
    public function strings() : array {
        return [
            'search'           => [
                'label'             => __( 'Search for artist', 'tms-theme-filharmonia' ),
                'submit_value'      => __( 'Search', 'tms-theme-filharmonia' ),
                'input_placeholder' => __( 'Search query', 'tms-theme-filharmonia' ),
            ],
            'terms'            => [
                'show_all' => __( 'Show All', 'tms-theme-filharmonia' ),
            ],
            'no_results'       => __( 'No results', 'tms-theme-filharmonia' ),
            'filter'           => __( 'Filter', 'tms-theme-filharmonia' ),
            'is_concertmaster' => __( 'Principal', 'tms-theme-filharmonia' ),
            'is_principal'     => __( 'Soundmaster', 'tms-theme-filharmonia' ),
        ];
    }

    /**
     * Modify query
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    public static function modify_query( WP_Query $wp_query ) : void {
        if ( is_admin() || ( ! $wp_query->is_main_query() || ! $wp_query->is_post_type_archive( Artist::SLUG ) ) ) {
            return;
        }

        $wp_query->set( 'posts_per_page', - 1 );
        $wp_query->set( 'orderby',
            [
                'menu_order_clause' => 'ASC',
                'last_name_clause'  => 'ASC',
            ]
        );
        $wp_query->set(
            'meta_query',
            [
                'menu_order_clause' => [
                    'key'  => 'menu_order',
                    'type' => 'NUMERIC',
                ],
                'last_name_clause'  => [
                    'key' => 'last_name',
                ],
            ]
        );

        $artist_category = self::get_filter_query_var();

        if ( ! empty( $artist_category ) ) {
            $wp_query->set(
                'tax_query',
                [
                    [
                        'taxonomy' => ArtistCategory::SLUG,
                        'terms'    => $artist_category,
                    ],
                ]
            );
        }

        $s = self::get_search_query_var();

        if ( ! empty( $s ) ) {
            $wp_query->set( 's', $s );
        }
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        $this->search_data        = new stdClass();
        $this->search_data->query = get_query_var( self::SEARCH_QUERY_VAR );

        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'action'            => get_post_type_archive_link( Artist::SLUG ),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {
        $categories = get_terms( [
            'taxonomy'   => ArtistCategory::SLUG,
            'hide_empty' => true,
        ] );

        if ( empty( $categories ) || is_wp_error( $categories ) ) {
            return [];
        }

        $categories = $this->sort_terms( $categories );

        $base_url   = get_post_type_archive_link( Artist::SLUG );
        $categories = array_map( function ( $item ) use ( $base_url ) {
            return [
                'name'      => $item->name,
                'url'       => add_query_arg(
                    [
                        self::FILTER_QUERY_VAR => $item->term_id,
                    ],
                    $base_url
                ),
                'is_active' => $item->term_id === self::get_filter_query_var(),
            ];
        }, $categories );

        array_unshift(
            $categories,
            [
                'name'      => __( 'All', 'tms-theme-filharmonia' ),
                'url'       => $base_url,
                'is_active' => null === self::get_filter_query_var(),
            ]
        );

        return $categories;
    }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        global $wp_query;

        $posts = $this->format_posts( $wp_query->posts );

        if ( $this->is_filtered() ) {
            return [
                [
                    'posts' => $posts,
                ],
            ];
        }

        $results = [];

        foreach ( $posts as $post_item ) {
            if ( empty( $post_item->categories ) ) {
                continue;
            }

            foreach ( $post_item->categories as $term ) {
                if ( empty( $results[ $term->term_id ] ) ) {
                    $results[ $term->term_id ] = $term;
                }

                if ( empty( $results[ $term->term_id ]->posts ) ) {
                    $results[ $term->term_id ]->posts = [ $post_item ];
                }
                else {
                    $results[ $term->term_id ]->posts[] = $post_item;
                }
            }
        }

        return $this->sort_terms( $results );
    }

    /**
     * Do we have posts?
     *
     * @return bool
     */
    public function have_posts() {
        global $wp_query;

        return $wp_query->have_posts();
    }

    /**
     * Is category filter active?
     *
     * @return bool
     */
    public function is_filtered() {
        return ! empty( $this->get_filter_query_var() );
    }

    /**
     * Format posts for view
     *
     * @param array $posts Array of WP_Post instances.
     *
     * @return array
     */
    public static function format_posts( array $posts ) : array {
        return array_map( function ( $item ) {
            if ( has_post_thumbnail( $item->ID ) ) {
                $item->image = get_post_thumbnail_id( $item->ID );
            }

            $item->permalink  = get_the_permalink( $item->ID );
            $item->fields     = get_fields( $item->ID );
            $item->categories = wp_get_post_terms( $item->ID, ArtistCategory::SLUG );

            if ( ! empty( $item->fields['description'] ) ) {
                $item->fields['description'] = wp_trim_words( $item->fields['description'], 18 );
            }

            $item->link = [
                'url'          => $item->permalink,
                'title'        => __( 'View', 'tms-theme-filharmonia' ),
                'icon'         => 'chevron-right',
                'icon_classes' => 'icon--medium',
            ];

            return $item;
        }, $posts );
    }

    /**
     * Sort terms by menu_order.
     *
     * @param array $terms Array of WP_Terms.
     *
     * @return array
     */
    protected function sort_terms( $terms ) {
        $terms = array_map( function ( $term ) {
            $term->menu_order = \get_field( 'menu_order', $term );

            return $term;
        }, $terms );

        uasort( $terms, fn( $a, $b ) => (int) $a->menu_order > (int) $b->menu_order );

        return $terms;
    }
}
