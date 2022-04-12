<?php 
/**
 * Constructor de Queries
 * 
 */

namespace Fworm\Builder; 

Trait QueryBuilder
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

    /**
     * Construye la clausula WHERE para el Query dependiendo del tipo.
     *
     * @return string
     **/
    public function buildWhere() : string
    {
        $closure = '';

        $countWheres = count($this->wheres);
        if ($countWheres <= 0) {
            return $closure;
        }

        foreach ($this->wheres as $index => $where) {
            if ( ($countWheres - 1) == $index ) {
                $where['boolean'] = '';
            }
            
            $method = 'build' . ucfirst($where['type']) . 'Where';

            if ( !isset( $where['type'] ) || $where['type'] == 'basic' ) {
                $closure .= $this->buildBasicWhere($where);
            } else if ( method_exists($this, $method) ) {
                $closure .= $this->{$method}($where);
            } else if ( WP_DEBUG ) {
                // TODO: Mensaje de error
            }
        }

        return $closure;
    }

    /**
     * Where tipo: basic.
     *
     * @param array $where 
     * @return string
     **/
    public function buildBasicWhere(array $where) : string
    {
        return "{$where['column']} {$where['operator']} '{$where['value']}' {$where['boolean']} ";
    }

    /**
     * Where tipo: between.
     *
     * @param array $where 
     * @return string
     **/
    public function buildBetweenWhere(array $where) : string
    {
        $not = $this->whereNotValidate( $where );
        return "{$where['column']} {$not} {$where['type']} '{$where['values'][0]}' AND '{$where['values'][1]}' {$where['boolean']} ";
    }
    
    /**
     * Where tipo: null.
     *
     * @param array $where 
     * @return string
     **/
    public function buildNullWhere(array $where) : string
    {
        $not = $this->whereNotValidate( $where );
        return "{$where['column']} IS {$not} {$where['type']} {$where['boolean']} ";
    }

    /**
     * Where tipo: in.
     *
     * @param array $where 
     * @return string
     **/
    public function buildInWhere(array $where) : string
    {
        if ( !is_array($where['values']) || count($where['values']) <= 0 ) {
            return '';
        }

        $values = implode(', ', $where['values']);
        $not = $this->whereNotValidate( $where );

        return "{$where['column']} {$not} {$where['type']} ({$values}) {$where['boolean']} ";
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