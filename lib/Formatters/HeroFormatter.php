<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Filharmonia\Formatters;

/**
 * Class HeroFormatter
 *
 * @package TMS\Theme\Base\Formatters
 */
class HeroFormatter implements \TMS\Theme\Base\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'Hero';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/hero/data',
            [ $this, 'format' ],
            30
        );
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function format( array $layout ) : array {

        $layout['heading_padding_size'] = isset( $layout['align'] ) && $layout['align'] === 'center' ? '4' : '2';

        // handle $layout['text_align'] OR $layout['align'] 
        if(isset( $layout['text_align'] )){

            $layout['align'] = $layout['text_align'];

        } else if( isset( $layout['align'] ) ){
            switch ( $layout['align'] ) {
                case 'right':
                    $layout['align'] = 'has-text-right';
                    break;
                case 'left':
                    $layout['align'] = 'has-text-right';
                    break;
                default:
                    $layout['align'] = 'has-text-centered';
            }
         } else{
            $layout['align'] = 'has-text-centered';
         }

        $layout['columns'] = [
            [
                'title' => $layout['opening_times']['opening_times_title'] ?? false,
                'text'  => $layout['opening_times']['opening_times_text'] ?? false,
                'link'  => $layout['opening_times']['opening_times_button'] ?? false,
            ],
            [
                'title' => $layout['tickets']['tickets_title'] ?? false,
                'text'  => $layout['tickets']['tickets_text'] ?? false,
                'logo'  => $layout['tickets']['tickets_image'] ?? false,
                'link'  => $layout['tickets']['tickets_button'] ?? false,
            ],
            [
                'title' => $layout['find_us']['find_us_title'] ?? false,
                'text'  => $layout['find_us']['find_us_text'] ?? false,
                'link'  => $layout['find_us']['find_us_button'] ?? false,
            ],
        ];

        return $layout;
    }
}
