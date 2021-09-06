<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia;

use TMS\Theme\Base\Interfaces\Controller;

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
}
