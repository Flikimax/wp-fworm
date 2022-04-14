<?php 
/**
 * Constructor de Queries
 * 
 */

namespace Fworm\Builder; 

Trait Query
{
    use Where;

    protected function buildSelect( $where = true )
    {
        $this->query = "SELECT * FROM {$this->table} ";
        if ( $where ) {
            if ( $whereString = $this->buildWhere() ) {
                $this->query .= 'WHERE ' . $this->buildWhere();
            }
        }

        $query = $this->query;
        $this->resetProperty('query');

        return $query;
    }

    
    # ============================== # 
    # =========== WHERES =========== #
    # ============================== # 

   





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

    /**
     * Verifica si un where tiene una condición not.
     *
     * @param array $where 
     * @return string
     **/
    protected function whereNotValidate( array $where ) : string
    {
        if ( !isset($where['not']) || $where['not'] === false ) {
            return '';
        }

        return empty( $where['not'] ) ? '' : 'NOT';
    }

}