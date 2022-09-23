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
