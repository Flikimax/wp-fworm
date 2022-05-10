<?php 
/**
 * Constructor de Wheres.
 * 
 */

namespace Fworm\Builder; 

Trait Select
{
    /**
     * Construye el select de la consulta.
     *
     * @return string
     **/
    public function buildSelect() : string
    {
        if ( count($this->bindings['select']) <= 0 ) {
            $this->bindings['select'][] = '*';
        }

        return implode(', ', $this->bindings['select']);
    }

}
