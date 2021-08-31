<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Base\ACF\Field\TextEditor;
use TMS\Theme\Base\Logger;
use TMS\Theme\Filharmonia\PostType;

/**
 * Class ArtistGroup
 *
 * @package TMS\Theme\Filharmonia\ACF
 */
class ArtistGroup {

    /**
     * ArtistGroup constructor.
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
            $field_group = ( new Group( 'Taiteilijan lisätiedot' ) )
                ->set_key( 'fg_artist_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Artist::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_details_tab( $field_group->get_key() ),
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

    /**
     * Get details tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_details_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'                    => 'Lisätiedot',
            'first_name'             => [
                'title'        => 'Etunimi',
                'instructions' => '',
            ],
            'last_name'              => [
                'title'        => 'Sukunimi',
                'instructions' => '',
            ],
            'title'                  => [
                'title'        => 'Titteli',
                'instructions' => '',
            ],
            'description'            => [
                'title'        => 'Kuvaus',
                'instructions' => '',
            ],
            'additional_information' => [
                'title'        => 'Lisätiedot',
                'instructions' => '',
                'button'       => 'Lisää rivi',
                'group'        => [
                    'title'        => 'Otsikko',
                    'instructions' => '',
                ],
                'item'         => [
                    'label' => [
                        'title'        => 'Otsikko',
                        'instructions' => '',
                    ],
                    'value' => [
                        'title'        => 'Teksti',
                        'instructions' => '',
                    ],
                ],
            ],
            'is_concertmaster'       => [
                'title'        => 'Konserttimestari',
                'instructions' => '',
            ],
            'is_principal'           => [
                'title'        => 'Äänenjohtaja',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $first_name_field = ( new Field\Text( $strings['first_name']['title'] ) )
            ->set_key( "${key}_first_name" )
            ->set_name( 'first_name' )
            ->redipress_include_search()
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['first_name']['instructions'] );

        $last_name_field = ( new Field\Text( $strings['last_name']['title'] ) )
            ->set_key( "${key}_last_name" )
            ->set_name( 'last_name' )
            ->redipress_include_search()
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['last_name']['instructions'] );

        $title_field = ( new Field\Text( $strings['title']['title'] ) )
            ->set_key( "${key}_title" )
            ->set_name( 'title' )
            ->redipress_include_search()
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new TextEditor( $strings['description']['title'] ) )
            ->set_key( "${key}_description" )
            ->set_name( 'description' )
            ->redipress_include_search()
            ->set_instructions( $strings['description']['instructions'] );

        $additional_info_repeater = ( new Field\Repeater(
            $strings['additional_information']['title']
        ) )
            ->set_key( "${key}_additional_information" )
            ->set_name( 'additional_information' )
            ->set_layout( 'block' )
            ->set_button_label( $strings['additional_information']['button'] );

        $additional_info_group = ( new Field\Group( $strings['additional_information']['group']['title'] ) )
            ->set_key( "${key}_additional_information_group" )
            ->set_name( 'additional_information_group' )
            ->set_instructions( $strings['additional_information']['group']['instructions'] );

        $additional_info_title = ( new Field\Text( $strings['additional_information']['item']['label']['title'] ) )
            ->set_key( "${key}_additional_information_title" )
            ->set_name( 'additional_information_title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['item']['label']['instructions'] );

        $additional_info_text = ( new Field\Text( $strings['additional_information']['item']['value']['title'] ) )
            ->set_key( "${key}_additional_information_text" )
            ->set_name( 'additional_information_text' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['item']['value']['instructions'] );

        $additional_info_group->add_fields( [ $additional_info_title, $additional_info_text ] );
        $additional_info_repeater->add_field( $additional_info_group );

        $is_concertmaster_field = ( new Field\TrueFalse( $strings['is_concertmaster']['title'] ) )
            ->set_key( "${key}_is_concertmaster" )
            ->set_name( 'is_concertmaster' )
            ->use_ui()
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['is_concertmaster']['instructions'] );

        $is_principal_field = ( new Field\TrueFalse( $strings['is_principal']['title'] ) )
            ->set_key( "${key}_is_principal" )
            ->set_name( 'is_principal' )
            ->use_ui()
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['is_principal']['instructions'] );

        $tab->add_fields( [
            $first_name_field,
            $last_name_field,
            $title_field,
            $description_field,
            $additional_info_repeater,
            $is_concertmaster_field,
            $is_principal_field,
        ] );

        return $tab;
    }
}

( new ArtistGroup() );