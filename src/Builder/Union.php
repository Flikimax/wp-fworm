<?php 
/**
 * Constructor de Uniones.
 * 
 */

namespace Fworm\Builder; 

Trait Union
{
    /**
     * Construye uniones de consulta.
     *
     * @return string
     **/
    public function buildUnion() : string
    {
        $query = '';
        if ( $this->unions ) {
            foreach ( $this->unions as $union ) {
                $query .= ' UNION ' . $union ;
            }
        }

        return $query;
    }

}
