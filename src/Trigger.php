<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm;

Trait Trigger
{
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


    /**
     * Disparadores de consulta.
     * 
     * @param string $output Tipo de salida de los datos: ARRAY_A | ARRAY_N | OBJECT | OBJECT_K.
     * @return array|object|null
     */

    /**
     * Retorna los datos dependiendo la consulta configurada.
     *
     * @param string $output
     * @return array|object|null
     **/
    protected function get(string $output = '') : array|null
    {
        $hasWhere = ( count( $this->wheres ) > 0 ) ? true : false;
        
        return $this->results( 
            $this->output($output),
            'buildSelect',
            [$hasWhere]
        );
    }
    
    /**
     * Retornar todos los datos de la consulta.
     *
     * @param string $output
     * @return array|object|null
     **/
    protected function all(string $output = '') : array|null
    {
        return $this->results( 
            $output,
            'buildSelect',
            [false]
        );
    }

    /**
     * Retorna el primer elemento de la consulta.
     *
     * @param string $output
     * @return array|object|null
     **/
    protected function first(string $output = '') : array|object|null
    {
        $results = $this->get( $output );
        if ( $results ) {
            $result = array_shift( $results );
        }

        return $result;
    }

    /**
     * Retorna el último elemento de la consulta.
     *
     * @param string $output
     * @return array|object|null
     **/
    protected function last(string $output = '') : array|object|null
    {
        $results = $this->get( $output );
        if ( $results ) {
            $result = array_pop( $results );
        }

        return $result;
    }

    /**
     * Retorna el elemento de la consulta que coincidas.
     *
     * @param int|string $int
     * @param string $output
     * @return array|object|null
     **/
    protected function find(int|string $id, string $output = '') : array|object|null
    {
        if ( empty($this->primaryKey) ) {
            return null;
        }

        $this->where($this->primaryKey, $id);
        return $this->get($output);
    }




    # =============================== # 
    # =========== HELPERS =========== #
    # =============================== #

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

    protected function results( string $output, $callbak, array $args = [] ) : mixed
    {
        $result = $this->wpdb->get_results( 
            $this->$callbak( ...$args ), 
            $output 
        );

        if ( $this->wpdb->last_error === '' ) {
            return $result;
        }

        if ( WP_DEBUG === true ) { ?>
            <p>
                <strong>Error: </strong> <?=$this->wpdb->last_error; ?>
            </p>
            <script>
                console.error( `Error (<?=WP_FWORM_NAME; ?>): <?=$this->wpdb->last_error; ?>` );
            </script>
        <?php }

        return null;
    }
}
