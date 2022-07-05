<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm;

Trait Trigger
{
    use \Fworm\Triggers\SelectTrigger,
        \Fworm\Triggers\InsertTrigger;

    /**
     * @var string $output Tipo de salida de los datos: ARRAY_A | ARRAY_N | OBJECT | OBJECT_K.
     * Más información en: https://www.php.net/manual/es/language.oop5.magic.php
     **/
    protected string $output = 'OBJECT_K';
    /** @var array $outputs Outputs permitidos */
    protected array $outputs = [
        'ARRAY_A',
        'ARRAY_N',
        'OBJECT',
        'OBJECT_K'
    ];


    # =================================
    # ============ Helpers ============
    # =================================

    /**
     * Valida que el output sea válido.
     *
     * @param string $output 
     * @return string
     **/
    protected function output(string $output) : string
    {
        return ( in_array($output, $this->outputs) ) ? $output : $this->output;
    }

    /**
     * Se valida si la consulta tuvo algun error.
     *
     * @return bool
     **/
    private function checkError() : bool
    {
        if ( $this->wpdb->last_error === '' ) {
            return true;
        }

        if ( WP_DEBUG ) { ?>
            <p>
                <strong>Error: </strong> <?=$this->wpdb->last_error; ?>
            </p>
            <script>
                console.error( `Error (<?=WP_FWORM_NAME; ?>): <?=$this->wpdb->last_error; ?>` );
            </script>
        <?php }

        return false;
    }

}
