<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\MainModel;

Trait General
{
    /**
     * Retorna el número total de registros.
     *
     * @return int|string
     **/
    protected function count() : int|string
    {
        $wpdb = $this->wpdb;

        $result = $wpdb->get_results( 
            "SELECT COUNT(*) AS total FROM {$this->table}", 
            'OBJECT'
        );

        if ( $wpdb->last_error !== '' || !isset($result[0]) && WP_DEBUG ) { ?>
                <p>
                    <strong>Error: </strong> <?=$this->wpdb->last_error; ?>
                </p>
                <script>
                    console.error( `Error (<?=WP_FWORM_NAME; ?>): <?=$this->wpdb->last_error; ?>` );
                </script>
        <?php }

        return (int) $result[0]->total;
    }

}