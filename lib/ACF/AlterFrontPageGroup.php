<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia\ACF;

use TMS\Theme\Base\ACF\Layouts\HeroLayout as BaseThemeHeroLayout;
use TMS\Theme\Filharmonia\ACF\Layouts\HeroLayout;

/**
 * Class AlterPageFrontPageGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class AlterPageFrontPageGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_filter( 'tms/acf/field/fg_front_page_components_components/layouts',
            [ $this, 'replace_base_theme_hero' ],
            10,
            1
        );
    }

    /**
     * Replace base theme hero with Filharmonia hero.
     *
     * @param array $layouts Front page layouts.
     *
     * @return mixed
     */
    public function replace_base_theme_hero( $layouts ) {
        $layouts = array_filter( $layouts, function ( $layout ) {
            return $layout !== BaseThemeHeroLayout::class;
        } );

        $layouts[] = HeroLayout::class;

        return $layouts;
    }
}

( new AlterPageFrontPageGroup() );
