<?php
/**
 * Template Name: Orkesteri
 */

use TMS\Theme\Filharmonia\PostType\Artist;
use TMS\Theme\Filharmonia\Taxonomy\ArtistCategory;

/**
 * Orchester page template.
 */
class PageOrchester extends BaseModel {

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
     * Results.
     *
     * @return array|int[]|string[]|WP_Term[]
     */
    public function results() {
        $terms = get_terms( [
            'taxonomy'   => ArtistCategory::SLUG,
            'hide_empty' => true,
            'orderby'    => 'meta_value_num',
            'meta_query' => [
                [
                    'key'  => 'menu_order',
                    'type' => 'NUMERIC',
                ],
            ],
        ] );

        return array_map( function ( $term ) {
            $args = [
                'post_type'      => Artist::SLUG,
                'posts_per_page' => 100,
                'post_status'    => 'publish',
                'orderby'        => [ 'last_name' => 'ASC' ],
                'meta_key'       => 'last_name',
                'tax_query'      => [
                    [
                        'taxonomy' => ArtistCategory::SLUG,
                        'terms'    => [ $term->term_id ],
                    ],
                ],
            ];

            $results = ( new WP_Query( $args ) )->posts;

            if ( empty( $results ) ) {
                return $term;
            }

            $term->posts = ArchiveArtist::format_posts( $results );

            return $term;
        }, $terms );
    }
}
