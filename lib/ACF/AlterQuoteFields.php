<?php

use TMS\Theme\Base\Logger;
use Geniem\ACF\Field;
use TMS\Theme\Filharmonia\ACF\Field\AccentColorField;

/**
 * Alter Quote Fields
 */
class AlterQuoteFields {

    /**
     * Constructor
     */
    public function __construct() {
        \add_filter(
            'tms/block/quote/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );
    }

    /**
     * Alter fields
     *
     * @param array  $fields Array of ACF fields.
     * @param string $key    Layout key.
     *
     * @return array
     */
    public function alter_fields( array $fields, string $key ) : array {

        $strings = [
            'author_title' => [
                'label'        => 'Sitaatin antajan titteli',
                'instructions' => '',
            ],
            'accent_color' => [
                'label'        => 'Taustaväri',
                'instructions' => '',
            ],
        ];

        try {
            // Add author title field
            $author_title_field = ( new Field\Text( $strings['author_title']['label'] ) )
                ->set_key( "{$key}_author_title" )
                ->set_name( 'author_title' )
                ->set_instructions( $strings['author_title']['instructions'] );
            array_push( $fields, $author_title_field );

            $accent_color_field = ( new Field\Select( $strings['accent_color']['label'] ) )
                ->set_key( "{$key}_accent_color" )
                ->set_name( 'accent_color' )
                ->use_ui()
                ->set_choices( [
                    'has-colors-white'           => 'Valkoinen (musta teksti)',
                    'has-colors-primary'         => 'Punainen (valkoinen teksti)',
                    'has-colors-red-light'       => 'Punainen, vaalennettu (musta teksti)',
                    'has-colors-darkblue'        => 'Tummansininen (valkoinen teksti)',
                    'has-colors-beige-light'     => 'Beige, vaalennettu (musta teksti)',
                    'has-colors-lightblue-light' => 'Vaaleansininen, vaalennettu (musta teksti)',
                ] )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['accent_color']['instructions'] );
            array_push( $fields, $accent_color_field );

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterQuoteFields() );
