<?php

use TMS\Theme\Base\Logger;
use Geniem\ACF\Field;

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
        ];

        try {
            // Add author title field
            $author_title_field = ( new Field\Text( $strings['author_title']['label'] ) )
                ->set_key( "{$key}_author_title" )
                ->set_name( 'author_title' )
                ->set_instructions( $strings['author_title']['instructions'] );

            $fields[] = $author_title_field;

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterQuoteFields() );
