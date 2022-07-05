<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm\Triggers;

Trait SelectTrigger
{
    /**
     * Retorna los datos de la consulta o null en caso de error.
     *
     * @param string $output
     * @param mixed $callbak
     * @param array $args
     * @return mixed
     **/
    protected function getResults( string $output, $callbak, array $args = [] ) : mixed
    {
        $results = $this->wpdb->get_results( 
            $this->$callbak( ...$args ), 
            $output 
        );
        
        return $this->checkError() ? $results : null;
    }


    /**
     * Disparadores de consulta.
     * 
     * @param string $output Tipo de salida de los datos: ARRAY_A | ARRAY_N | OBJECT | OBJECT_K.
     * @return array|object|null
     */

    /**
     * Retorna el query dependiendo la consulta configurada.
     *
     * @return null|string
     **/
    protected function query() : ?string
    {
        return $this->buildQuery('get');
    }

    /**
     * Retorna los datos dependiendo la consulta configurada.
     *
     * @param string $output
     * @return array|object|null
     **/
    protected function get(string $output = '') : array|null
    {
        $hasWhere = ( count( $this->wheres ) > 0 ) ? true : false;
        
        return $this->getResults( 
            $this->output($output),
            'buildQuery',
            ['get', ['hasWhere' => $hasWhere]]
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
        return $this->getResults( 
            $output,
            'buildQuery',
            // [false]
            ['get']
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

}
