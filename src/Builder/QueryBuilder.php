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
            $this->query .= 'WHERE ' . $this->buildWhere();
        }

        $query = $this->query;
        $this->resetProperty('query');

        return $query;
    }

    
    # WHERES
    public function buildWhere()
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

    public function buildBasicWhere(array $where) : string
    {
        return "{$where['column']} {$where['operator']} '{$where['value']}' {$where['boolean']} ";
    }

    
    public function buildBetweenWhere(array $where) : string
    {
        // WHERE fecha BETWEEN '2020-05-01' AND '2022-06-01'
        // WHERE fecha NOT BETWEEN 1997 AND 2022;

        $not = $where['not'] ? 'NOT' : '';
        return "{$where['column']} {$not} {$where['type']} '{$where['values'][1]}' AND '{$where['values'][0]}' {$where['boolean']} ";
    }


    
    protected function resetProperty(string $property) : bool
    {
        if ( property_exists($this, $property) ) {
            $type = (new \ReflectionProperty($this, $property))
                ->getType()
                ->getName();

            $this->{$property} = match ($type) {
                'string' => '',
                'object' => new stdClass(),
                'array' => [],
                'int' => 0,
                'bool' => false,
                'null' => null,
            };

            return true;
        }

        return false;
    }


}