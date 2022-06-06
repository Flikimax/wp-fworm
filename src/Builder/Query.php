<?php 
/**
 * Constructor de Queries
 * 
 */

namespace Fworm\Builder; 

Trait Query
{
    use Select,
        Where,
        LimitOffset,
        Union;

    protected function buildQuery( $where = true )
    {
        # Select
        $this->query = 'SELECT ' . $this->buildSelect();

        # From
        $this->query .= " FROM {$this->table} ";

        # Where
        if ( $where ) {
            if ( $whereString = $this->buildWhere() ) {
                $this->query .= 'WHERE ' . $this->buildWhere();
            }
        }

        # Limit
        $this->query .= $this->buildLimit();
        
        # Offset
        $this->query .= $this->buildOffset();

        # Union
        $this->query .= $this->buildUnion();

        $query = $this->query;
        $this->resetProperty('query');

        return $query;
    }

    # =============================== # 
    # =========== HELPERS =========== #
    # =============================== #

    /**
     * Reinicia una propiedad de la clase, buscando su tipo.
     *
     * @param string $property Nombre de la propiedad a reiniciar.
     * @return bool
     **/
    protected function resetProperty(string $property) : bool
    {
        if ( property_exists($this, $property) ) {
            $type = (new \ReflectionProperty($this, $property))
                ->getType()
                ->getName();

            $this->{$property} = match ($type) {
                'object' => new stdClass(),
                'bool'   => false,
                'null'   => null,
                'string' => '',
                'array'  => [],
                'int'    => 0,
            };

            return true;
        }

        return false;
    }

}