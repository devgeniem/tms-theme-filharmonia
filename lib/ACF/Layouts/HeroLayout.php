<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia\ACF\Layouts;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Flexible\Layout;
use TMS\Theme\Base\Logger;

/**
 * Class HeroLayout
 *
 * @package TMS\Theme\Filharmonia\ACF\Layouts
 */
class HeroLayout extends Layout {

    /**
     * Layout key
     */
    const KEY = '_hero';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Hero',
            $key . self::KEY,
            'hero'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields.
     *
     * @return array
     * @throws Exception In case of invalid option.
     */
    public function add_layout_fields() : array {
        $key = $this->get_key();

        $strings = [
            'image'         => [
                'label'        => 'Kuva',
                'instructions' => '',
            ],
            'overlay'       => [
                'label'        => 'Tummennus',
                'instructions' => '',
            ],
            'title'         => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'description'   => [
                'label'        => 'Kuvaus',
                'instructions' => '',
            ],
            'description_link' => [
                'label'        => 'Kuvauksen linkki',
                'instructions' => '',
            ],
            'link'          => [
                'label'        => 'Painike',
                'instructions' => '',
            ],
            'text_align'    => [
                'label'        => 'Tekstin tasaus',
                'instructions' => '',
            ],
            'opening_times' => [
                'label'  => 'Aukioloajat',
                'title'  => [
                    'label'        => 'Otsikko',
                    'instructions' => '',
                ],
                'text'   => [
                    'label'        => 'Teksti',
                    'instructions' => '',
                ],
                'button' => [
                    'label'        => 'Painike',
                    'instructions' => '',
                ],
            ],
            'tickets'       => [
                'label'  => 'Liput',
                'title'  => [
                    'label'        => 'Otsikko',
                    'instructions' => '',
                ],
                'text'   => [
                    'label'        => 'Teksti',
                    'instructions' => '',
                ],
                'button' => [
                    'label'        => 'Painike',
                    'instructions' => '',
                ],
                'image'  => [
                    'label'        => 'Kuva',
                    'instructions' => '',
                ],
            ],
            'find_us'       => [
                'label'  => 'Löydä meille',
                'title'  => [
                    'label'        => 'Otsikko',
                    'instructions' => '',
                ],
                'text'   => [
                    'label'        => 'Teksti',
                    'instructions' => '',
                ],
                'button' => [
                    'label'        => 'Painike',
                    'instructions' => '',
                ],
            ],
        ];

        $image_field = ( new Field\Image( $strings['image']['label'] ) )
            ->set_key( "${key}_image" )
            ->set_name( 'image' )
            ->set_return_format( 'id' )
            ->set_wrapper_width( 70 )
            ->set_required()
            ->set_instructions( $strings['image']['instructions'] );

        $overlay_field = ( new Field\TrueFalse( $strings['overlay']['label'] ) )
            ->set_key( "${key}_overlay" )
            ->set_name( 'overlay' )
            ->set_wrapper_width( 30 )
            ->use_ui()
            ->set_instructions( $strings['image']['instructions'] );

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "${key}_title" )
            ->set_name( 'title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new Field\Textarea( $strings['description']['label'] ) )
            ->set_key( "${key}_description" )
            ->set_name( 'description' )
            ->set_rows( 4 )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['description']['instructions'] );

        $align_field = ( new Field\Select( $strings['text_align']['label'] ) )
            ->set_key( "${key}_text_align" )
            ->set_name( 'text_align' )
            ->set_choices( [
                'has-text-left'     => 'Vasen',
                'has-text-right'    => 'Oikea',
                'has-text-centered' => 'Keskitetty',
            ] )
            ->set_default_value( 'has-text-centered' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['text_align']['instructions'] );

        $description_link_field = ( new Field\Link( $strings['description_link']['label'] ) )
            ->set_key( "${key}_description_link" )
            ->set_name( 'description_link' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['description_link']['instructions'] );

        $opening_times_tab = ( new Field\Group( $strings['opening_times']['label'] ) )
            ->set_key( "${key}_opening_times" )
            ->set_name( 'opening_times' );

        $opening_times_tab->add_fields(
            $this->get_hero_group_fields( $key, 'opening_times', $strings['opening_times'] )
        );

        $fields[] = $opening_times_tab;

        $ticket_tab = ( new Field\Group( $strings['tickets']['label'] ) )
            ->set_key( "${key}_tickets" )
            ->set_name( 'tickets' );

        $ticket_tab->add_fields( $this->get_hero_group_fields( $key, 'tickets', $strings['tickets'] ) );

        $find_us_tab = ( new Field\Group( $strings['find_us']['label'] ) )
            ->set_key( "${key}_find_us" )
            ->set_name( 'find_us' );

        $find_us_tab->add_fields(
            $this->get_hero_group_fields( $key, 'find_us', $strings['find_us'] )
        );

        try {
            $this->add_fields(
                apply_filters(
                    'tms/acf/layout/hero--filharmonia/fields',
                    [
                        $image_field,
                        $overlay_field,
                        $title_field,
                        $description_field,
                        $align_field,
                        $description_link_field,
                        $opening_times_tab,
                        $ticket_tab,
                        $find_us_tab,
                    ],
                    $key
                )
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $fields;
    }

    /**
     * Get hero group fields.
     *
     * @param string $key     Layout key.
     * @param string $group   Group name.
     * @param array  $strings Field strings.
     *
     * @return array
     * @throws Exception In case of invalid ACF option.
     */
    public function get_hero_group_fields( string $key, string $group, array $strings ) : array {
        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "${key}_${group}_title" )
            ->set_name( "${group}_title" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $text_field = ( new Field\Textarea( $strings['text']['label'] ) )
            ->set_key( "${key}_${group}_text" )
            ->set_name( "${group}_text" )
            ->set_new_lines( 'wpautop' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['text']['instructions'] );

        $button_field = ( new Field\Link( $strings['button']['label'] ) )
            ->set_key( "${key}_${group}_button" )
            ->set_name( "${group}_button" )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['button']['instructions'] );

        return [
            $title_field,
            $text_field,
            $button_field,
        ];
    }
}
