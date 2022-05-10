<?php 
/**
 * Constructor de Wheres.
 * 
 */

namespace Fworm\Builder; 

Trait LimitOffset
{


    /**
     * Agrega el limit para la consulta.
     *
     * @return string
     **/
    public function buildLimit() : string
    {
        return $this->limitOffset('limit');
    }

    /**
     * Agrega el offset para la consulta.
     *
     * @return string
     **/
    public function buildOffset() : string
    {
        if ( !$this->limitOffset('limit') ) {
            if ( WP_DEBUG ) {
                throw new \Exception( "Para usar correctamente la función 'offset' se requiere de un limit." );
            }
            return '';
        }

        return $this->limitOffset('offset');
    }


    /**
     * Construye el limit y/o el offset para la consulta.
     *
     * @param string $property
     * @return string
     **/
    public function limitOffset(string $property) : string
    {
        if ( $this->$property ) {
            return ' ' . strtoupper($property) . " {$this->$property}";
        }
        
        return '';
    }

}



