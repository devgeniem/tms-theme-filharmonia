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
    }

    /**
     * Customize header colors.
     *
     * @param array $colors Header color classes.
     *
     * @return array
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
}
