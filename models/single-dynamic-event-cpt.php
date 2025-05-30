<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\EventzClient;
use TMS\Theme\Base\Eventz;
use TMS\Theme\Base\Logger;
use TMS\Theme\Base\Traits\Components;
use TMS\Theme\Base\Traits;

/**
 * The SingleDynamicEventCpt class.
 */
class SingleDynamicEventCpt extends PageEvent {

    use Traits\Sharing;

    /**
     * Hooks
     */
    public function hooks() : void {
        \add_filter(
            'tms/base/breadcrumbs/after_prepare',
            Closure::fromCallable( [ $this, 'alter_breadcrumbs' ] )
        );
    }

    /**
     * Hero image
     *
     * @return false|int
     */
    public function hero_image() {
        return \has_post_thumbnail()
            ? \get_post_thumbnail_id()
            : false;
    }

    /**
     * Hero image URL
     *
     * @return false|string
     */
    public function hero_image_url() {
        return \has_post_thumbnail()
            ? \get_the_post_thumbnail_url()
            : false;
    }

    /**
     * Hero image graphic field
     *
     * @return false|string
     */
    public function hero_image_graphic() {
        $graphic_field = \get_field( 'graphic' );
        $hero_graphic  = null;

        if ( $graphic_field && $graphic_field !== 'none' ) {
            $hero_graphic = \get_stylesheet_directory_uri() . '/assets/images/' . $graphic_field . '.svg';
        }

        return $hero_graphic;
    }

    /**
     * Get event id
     *
     * @return string
     */
    protected function get_event_id() : string {
        return \get_field( 'event' ) ?? '';
    }

    /**
     * Alter breadcrumbs
     *
     * @param array $breadcrumbs Array of breadcrumbs.
     *
     * @return array
     */
    public function alter_breadcrumbs( array $breadcrumbs ) : array {
        $referer  = \wp_get_referer();
        $home_url = DPT_PLL_ACTIVE && function_exists( 'pll_current_language' )
            ? \pll_home_url()
            : \home_url();

        if ( false === strpos( $referer, $home_url ) ) {
            return $breadcrumbs;
        }

        $parent = \get_page_by_path(
            str_replace( $home_url, '', $referer )
        );

        if ( empty( $parent ) || \get_post_status( $parent ) !== 'publish' ) {
            return $breadcrumbs;
        }

        $last = array_pop( $breadcrumbs );

        $breadcrumbs[] = [
            'title'     => $parent->post_title,
            'permalink' => \get_the_permalink( $parent->ID ),
        ];

        $breadcrumbs[] = $last;

        return $breadcrumbs;
    }
}
