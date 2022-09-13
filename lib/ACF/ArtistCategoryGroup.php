<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Filharmonia\Taxonomy;

/**
 * Class ArtistCategoryGroup
 *
 * @package TMS\Theme\Filharmonia\ACF
 */
class ArtistCategoryGroup {

    /**
     * ArtistCategoryGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $strings = [
                'tab'        => 'Lisätiedot',
                'menu_order' => [
                    'title'        => 'Järjestysnumero',
                    'instructions' => '',
                ],
            ];

            $field_group = ( new Group( 'Muusikon lisätiedot' ) )
                ->set_key( 'fg_artist_category_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'taxonomy', '==', Taxonomy\ArtistCategory::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'side' );

            $menu_order_field = ( new Field\Number( $strings['menu_order']['title'] ) )
                ->set_key( $field_group->get_key() . '_menu_order' )
                ->set_name( 'menu_order' )
                ->set_default_value( 0 )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['menu_order']['instructions'] );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $menu_order_field,
                    ]
                )
            );

            $field_group = apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }
}

( new ArtistCategoryGroup() );
