<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\MainModel;

Trait Limit
{
    /**
     * Limite para la consulta.
     *
     * @param mixed $limit 
     * @return object $this
     **/
    protected function limit(mixed $limit) : object
    {
        if ( is_string($limit) && strtolower($limit) == 'all' ) {
            $limit = $this->count();
        }

        if ( is_int($limit) ) {
            $this->limit = $limit;
        }

        return $this;
    }

    # ============================= # 
    # =========== ALIAS =========== #
    # ============================= #

    /**
     * Alias de la función limit.
     *
     * @param mixed $limit 
     * @return object $this
     **/
    protected function take(mixed $limit) : object
    {
        return $this->limit( $limit );
    }

}