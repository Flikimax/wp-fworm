<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\MainModel;

Trait Offset
{
    /**
     * Registros que se omiten en la consulta.
     *
     * @param mixed $offset 
     * @return object $this
     **/
    protected function offset(mixed $offset) : object
    {
        if ( is_int($offset) ) {
            $this->offset = $offset;
        }

        return $this;
    }

    # ============================= # 
    # =========== ALIAS =========== #
    # ============================= #

    /**
     * Alias de la función offset.
     *
     * @param mixed $skip 
     * @return object $this
     **/
    protected function skip(mixed $skip) : object
    {
        return $this->offset( $skip );
    }

}