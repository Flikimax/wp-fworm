<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\MainModel;

Trait Select
{
    /**
     * Selección de columnas para la consulta.
     *
     * @param string|array $columns 
     * @return object $this
     **/
    protected function select(string|array ...$columns) : object
    {
        if ( count($columns)  <= 0 ) {
            $columns = ['*'];
        }

        # Si se envia un array.
        if ( is_array($columns[0]) ) {
            $columns = $columns[0];
        }

        # Se filtran los valores.
        $filtered = $this->selectFilter($columns);

        $this->bindings['select'] = array_merge($this->bindings['select'], $filtered);

        return $this;
    }

    /**
     * Filtra las columnas.
     *
     * @param array $columns Columnas a filtrar.
     * @return array
     **/
    public function selectFilter( array $columns ) : array
    {
        $filtered = [];
        foreach ( $columns as $column ) {
            if ( !is_string($column) ) {
                continue;
            }

            if ( $this->columns[$column] ?? false ) {
                $column = "{$column} AS " . $this->columns[$column];
            }

            $filtered[] = $column;
        }

        return $filtered;
    }

}