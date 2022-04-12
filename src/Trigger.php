<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm;

Trait Trigger
{

    /**
     * 
     *
     * @param mixed $output Tipo de saluda de los datos: ARRAY_A | ARRAY_N | OBJECT | OBJECT_K.
     * Más información en: https://www.php.net/manual/es/language.oop5.magic.php
     * 
     * @return array|object|null
     **/
    protected function get(string $output = 'OBJECT') : array|object|null
    {
        return $this->results( 
            $output,
            'buildSelect',
        );
    }
    
    /**
     * 
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    protected function all(string $output = 'OBJECT') : array|object|null
    {
        // return $this->wpdb->get_results( 
        //     $this->buildSelect(false), 
        //     $output 
        // );

        return $this->results( 
            $output,
            'buildSelect',
            [false]
        );
    }

    /**
     * 
     * Example::find(3)
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    protected function find($id, string $output = 'OBJECT')
    {
        $this->where($this->primaryKey, $id);
        return $this->get($output);
    }

    protected function first()
    {
        
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
