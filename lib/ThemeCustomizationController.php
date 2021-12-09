<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia;

use TMS\Theme\Base\Interfaces\Controller;
use WP_post;

/**
 * Class ThemeCustomizationController
 *
 * @package TMS\Theme\Filharmonia
 */
class ThemeCustomizationController implements Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'tms/theme/header/colors',
            \Closure::fromCallable( [ $this, 'header_colors' ] ),
            10,
            1
        );

        add_filter(
            'tms/theme/footer/colors',
            \Closure::fromCallable( [ $this, 'footer_colors' ] ),
            10,
            1
        );

        add_filter(
            'tms/theme/footer/typgraphy',
            \Closure::fromCallable( [ $this, 'footer_typography' ] ),
            10,
            1
        );

        add_filter(
            'tms/theme/share_links/link_class',
            fn() => 'has-background-primary-invert'
        );

        add_filter(
            'tms/theme/share_links/icon_class',
            fn() => 'is-black'
        );

        add_filter(
            'tms/theme/accent_colors',
            [ $this, 'get_theme_accent_colors' ]
        );

        add_filter(
            'tms/theme/search/search_item',
            [ $this, 'search_classes' ]
        );

        add_filter(
            'tms/theme/base/search_result_item',
            [ $this, 'alter_search_result_item' ]
        );

        add_filter(
            'tms/theme/event/hero_info_classes',
            fn() => 'has-colors-tertiary'
        );

        add_filter( 'tms/theme/event/group_title', function () {
            return [
                'title' => 'has-background-tertiary',
                'icon'  => 'is-accent',
            ];
        } );

        add_filter( 'tms/acf/block/material/data', function ( $data ) {
            $data['button_classes'] = 'is-primary';

            return $data;
        } );

        add_filter(
            'tms/plugin-materials/page_materials/material_page_item_button_classes',
            fn() => 'is-primary'
        );

        add_filter(
            'tms/plugin-materials/page_materials/material_page_item_classes',
            fn() => ''
        );

        add_filter( 'tms/theme/event/info_group_classes', fn() => '' );

        add_filter(
            'tms/acf/block/quote/data',
            [ $this, 'alter_quote_block_data' ]
        );


        add_filter(
            'tms/acf/block/subpages/data',
            [ $this, 'alter_block_subpages_data' ],
            30
        );
    }

    /**
     * Customize header colors.
     *
     * @param array $colors Color classes.
     *
     * @return array Array of customized colors.
     */
    public function header_colors( $colors ) : array {
        $colors['search_button']             = 'is-secondary-invert is-outlined';
        $colors['nav']['container']          = 'has-background-secondary';
        $colors['fly_out_nav']['inner']      = 'has-background-secondary has-text-secondary-invert';
        $colors['fly_out_nav']['close_menu'] = 'is-black';
        $colors['fly_out_nav']['search_title'] = 'is-black';
        $colors['fly_out_nav']['search_button'] = 'is-secondary-invert is-outlined';
        $colors['fly_out_nav']['brand_icon_color'] = 'is-black';
        $colors['lang_nav']['link']          = 'has-border-radius-50';
        $colors['lang_nav']['link__default'] = 'has-text-secondary-invert';
        $colors['lang_nav']['link__active']  = 'has-background-secondary-invert has-text-primary-invert';


        return $colors;
    }

    /**
     * Customize footer colors.
     *
     * @param array $colors Color classes.
     *
     * @return array Array of customized colors.
     */
    public function footer_colors( $colors ) : array {
        $colors['container']   = 'has-background-secondary has-text-secondary-invert';
        $colors['back_to_top'] = 'is-secondary-invert is-outlined';
        $colors['link']        = 'has-text-secondary-invert';
        $colors['link_icon']   = 'is-black';

        return $colors;
    }

     /**
     * Customize footer typogrphy.
     *
     * @param array $typography Typography classes.
     *
     * @return array Array of customized typography classes.
     */
    public function footer_typography( $colors ) : array {
        $typography['column']   = 'has-text-weight-normal is-family-secondary has-text-small';
        return $typography;
    }

    /**
     * Set grid item color scheme.
     *
     * @return string
     */
    public function set_grid_item_color() {
        return 'secondary';
    }

    /**
     * Theme accent colors for layouts
     *
     * @return string[]
     */
    public function get_theme_accent_colors() : array {
        return [
            'has-colors-primary'          => 'Punaoranssi (valkoinen teksti)',
            'has-colors-accent-secondary' => 'Harmaa (musta teksti)',
        ];
    }

    /**
     * Search classes.
     *
     * @param array $classes Search view classes.
     *
     * @return array
     */
    public function search_classes( $classes ) : array {
        $classes['search_item']          = 'has-border-1 has-border-primary';
        $classes['search_form_button']   = 'is-secondary-invert is-outlined';
        $classes['event_search_section'] = 'has-border-bottom-1 has-border-top-1 has-border-divider-invert';

        return $classes;
    }

    /**
     * Quote
     *
     * @param array $data Quote block data.
     *
     * @return array
     */
    public function alter_quote_block_data( $data ) : array {
        $data['classes']['container'] = [
            'mt-6',
            'mb-6',
            'pt-6',
            'pb-6',
        ];
        $data['classes']['quote']     = [
            'is-text-big',
            'has-line-height-tight',
            'is-family-tertiary',
            'is-uppercase',
            'has-text-centered',
        ];

        return $data;
    }

    /**
     * Alter Subpages block data.
     *
     * @param array $data Block data.
     *
     * @return array
     */
    public function alter_block_subpages_data( $data ) {
        if ( empty( $data['subpages'] ) ) {
            return $data;
        }

        $icon_colors_map = [
            'black'     => 'is-secondary-invert',
            'white'     => 'is-primary',
            'primary'   => 'is-black-invert',
            'secondary' => 'is-secondary-invert',
        ];

        $icon_color_key = $data['background_color'] ?? 'black';

        $data['icon_classes'] = $icon_colors_map[ $icon_color_key ];

        return $data;
    }

    /**
     * Get theme accent color by key
     *
     * @param string $key Accent color key.
     *
     * @return string|null
     */
    public function get_theme_accent_color_by_key( string $key ) : ?string {
        $map = $this->get_theme_accent_colors();

        return $map[ $key ] ?? null;
    }

    /**
     * Alter search result item
     *
     * @param WP_Post $post_item Instance of \WP_Post.
     *
     * @return WP_post
     */
    public function alter_search_result_item( $post_item ) {
        $post_item->content_type = false;

        return $post_item;
    }
}
