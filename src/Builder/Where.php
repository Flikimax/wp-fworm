<?php 
/**
 * Constructor de Wheres.
 * 
 */

namespace Fworm\Builder; 

Trait Where
{
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

    # ==================================================== # 
    # =========== Day, Month, Year, Time, Date =========== #
    # =============== DateTime, TimeStamp  =============== #
    # ==================================================== # 

    /**
     * Where tipo: day.
     *
     * @param array $where 
     * @return string
     **/
    public function buildDayWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }

    /**
     * Where tipo: month.
     *
     * @param array $where 
     * @return string
     **/
    public function buildMonthWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }

    /**
     * Where tipo: year.
     *
     * @param array $where 
     * @return string
     **/
    public function buildYearWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }

    /**
     * Where tipo: time.
     *
     * @param array $where 
     * @return string
     **/
    public function buildTimeWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }

    /**
     * Where tipo: date.
     *
     * @param array $where 
     * @return string
     **/
    public function buildDateWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }

    /**
     * Where tipo: datetime.
     *
     * @param array $where 
     * @return string
     **/
    public function buildDateTimeWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }

    /**
     * Where tipo: timestamp.
     *
     * @param array $where 
     * @return string
     **/
    public function buildTimeStampWhere(array $where) : string
    {
        return $this->buildGeneralDateWhere( $where, $where['type'] );
    }
    
    # =============================== # 
    # =========== HELPERS =========== #
    # =============================== #

    /**
     * Where tipo: day, month y year.
     *
     * @param array  $where 
     * @param string $type 
     * @return string
     **/
    public function buildGeneralDateWhere(array $where, string $type) : string
    {
        $type = strtoupper( $type );
        return "$type({$where['column']}) {$where['operator']} '{$where['value']}' {$where['boolean']} ";
    }

}