<?php

namespace TMS\Theme\Amuri\ACF;

use Geniem\ACF\Field;

/**
 * Class AlterDynamicEventGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class AlterDynamicEventGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        \add_filter( 'tms/acf/group/fg_dynamic_event_fields/fields',
            [ $this, 'add_graphic_field' ],
            10,
            1
        );
    }

    /**
     * Replace base theme hero with Muumimuseo hero.
     *
     * @param array $layouts Front page layouts.
     *
     * @return mixed
     */
    public function add_graphic_field( $fields ) {
        $strings = [
            'tab'     => 'Kuvan grafiikka',
            'graphic' => [
                'label'        => 'Grafiikka',
                'instructions' => '',
                'choices'      => [
                    'none'                        => 'Ei grafiikkaa',
                    'graphic_grafiikka_1'         => 'Grafiikka 1',
                    'graphic_grafiikka_2'         => 'Grafiikka 2',
                    'graphic_grafiikka_3'         => 'Grafiikka 3',
                    'graphic_oikea_iso_1'         => 'Oikea iso 1',
                    'graphic_oikea_iso_2'         => 'Oikea iso 2',
                    'graphic_oikea_iso_3'         => 'Oikea iso 3',
                    'graphic_vasen_iso_1'         => 'Vasen iso 1',
                    'graphic_vasen_iso_2'         => 'Vasen iso 2',
                    'graphic_vasen_keski_1'       => 'Vasen keski 1',
                    'graphic_vasen_keski_2'       => 'Vasen keski 2',
                    'graphic_vasen_pieni_1'       => 'Vasen pieni 1',
                    'graphic_vasen_pieni_2'       => 'Vasen pieni 2',
                    'graphic_fauni_verkkosivut_1' => 'Fauni verkkosivut 1',
                    'graphic_fauni_verkkosivut_2' => 'Fauni verkkosivut 2',
                    'graphic_fauni_verkkosivut_3' => 'Fauni verkkosivut 3',
                    'graphic_fauni_verkkosivut_4' => 'Fauni verkkosivut 4',
                ],
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $graphic_field = ( new Field\Select( $strings['graphic']['label'] ) )
            ->set_key( "fg_dynamic_event_fields_graphic" )
            ->set_name( 'graphic' )
            ->set_choices( $strings['graphic']['choices'] )
            ->set_instructions( $strings['graphic']['instructions'] );

        $tab->add_field( $graphic_field );

        $fields[] = $tab;

        return $fields;
    }
}

( new AlterDynamicEventGroup() );
