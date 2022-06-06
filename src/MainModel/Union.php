<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\MainModel;

Trait Union
{
    /**
     * Agrega una consulta a la propiedad union.
     *
     * @param  string $query
     * @return object This.
     **/
    protected function union( string $query ) : object
    {
        $this->unions[] = $query;
        return $this;
    }

}