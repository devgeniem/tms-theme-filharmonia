<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia;

/**
 * Class ThemeSupports
 *
 * @package TMS\Theme\Filharmonia
 */
class ThemeSupports implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter(
            'query_vars',
            \Closure::fromCallable( [ $this, 'query_vars' ] )
        );

        /**
         * Allow custom url links in menus
         */
        \add_filter(
            'tms/theme/remove_custom_links',
            '__return_false'
        );
    }

    /**
     * Append custom query vars
     *
     * @param array $vars Registered query vars.
     *
     * @return array
     */
    protected function query_vars( $vars ) {
        $vars[] = \ArchiveArtist::SEARCH_QUERY_VAR;
        $vars[] = \ArchiveArtist::FILTER_QUERY_VAR;

        return $vars;
    }
}
