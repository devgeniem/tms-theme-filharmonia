<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia;

use TMS\Theme\Filharmonia\Taxonomy\ArtistCategory;
use function load_child_theme_textdomain;
use function load_theme_textdomain;

/**
 * Class Localization
 *
 * @package TMS\Theme\Filharmonia
 */
class Localization extends \TMS\Theme\Base\Localization implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Load theme translations.
     */
    public function load_theme_textdomains() {
        load_theme_textdomain(
            'tms-theme-base',
            get_template_directory() . '/lang'
        );

        load_child_theme_textdomain(
            'tms-theme-filharmonia',
            get_stylesheet_directory() . '/lang'
        );
    }

    /**
     * This adds the CPTs that are not public to Polylang translation.
     *
     * @param array   $post_types  The post type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified post_types -array.
     */
    protected function add_cpts_to_polylang( $post_types, $is_settings ) : array { // phpcs:ignore
        if ( ! DPT_PLL_ACTIVE ) {
            return $post_types;
        }

        $post_types[ PostType\Artist::SLUG ] = PostType\Artist::SLUG;

        return $post_types;
    }

    /**
     * This adds the taxonomies that are not public to Polylang translation.
     *
     * @param array   $tax_types   The taxonomy type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified tax_types -array.
     */
    protected function add_tax_to_polylang( $tax_types, $is_settings ) : array { // phpcs:ignore
        $tax_types[ ArtistCategory::SLUG ] = ArtistCategory::SLUG;

        return $tax_types;
    }
}
