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

    /**
     * Undocumented function long description
     *
     * @param string $type Tipo de Query.
     * @param array  $args Parametros extras que se puedan llegar a requerir en cada tipo de query a construir.
     * @return string $query La query construida.
     * @throws \Exception Si no hay match con un tipo de query.
     **/
    protected function buildQuery( string $type, array $args = [] ) : string
    {
        $type = strtolower($type);
        if ( $type === 'get' ) {
            $this->buildQuerySelect( $args );
        } else if ( $type === 'insert' ) {
            $this->buildQueryInsert( $args );
        } else if ( $type === 'update' ) {
            
        } else if ( $type === 'delete' ) {
            
        } else {
            throw new \Exception( "The '{$type}' method does not exist." );
        }

        $query = $this->query;
        $this->resetProperty('query');

        return $query;
    }
    

    /**
     * Construye y retorna un query de tipo SELECT.
     *
     * @param array $args
     * @return string
     **/
    protected function buildQuerySelect( array $args) : string
    {
        # Select
        $this->query = 'SELECT ' . $this->buildSelect();

        # From
        $this->query .= " FROM {$this->table} ";

        # Where
        if ( isset($args['hasWhere']) && $args['hasWhere'] ) {
            if ( $whereString = $this->buildWhere() ) {
                $this->query .= 'WHERE ' . $whereString;
            }
        }

        # Limit
        $this->query .= $this->buildLimit();
        
        # Offset
        $this->query .= $this->buildOffset();

        # Union
        $this->query .= $this->buildUnion();

        return $this->query;
    }

    /**
     * Construye y retorna un query de tipo INSERT.
     *
     * @param array $args
     * @return string
     **/
    protected function buildQueryInsert( array $args ) : string
    {
        

        return '';
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