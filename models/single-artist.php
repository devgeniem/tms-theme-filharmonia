<?php
/**
 * Define the single artist class.
 */

use DustPress\Query;
use TMS\Theme\Base\Traits;

/**
 * The SingleArtist class.
 */
class SingleArtist extends BaseModel {

    use Traits\Sharing;

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        return Query::get_acf_post();
    }
}
