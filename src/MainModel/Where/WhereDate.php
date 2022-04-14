<?php
/**
 * Clausulas Where de tipo Date.
 * 
 */

namespace Fworm\MainModel\Where;

Trait WhereDate
{
    # ==================================================== # 
    # =========== Day, Month, Year, Time, Date =========== #
    # =============== DateTime, TimeStamp  =============== #
    # ==================================================== # 

    /**
     * Añade un where tipo day para la consulta.
     * 
     * Filtra los elementos en los que el dia coincida.
     * 
     * Example::whereDay('created_at', 21);
     * Example::whereDay('created_at', '<=', 19);
     * Example::whereDay('created_at', '<=', 10, 'or');
     *
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereDay(string $column, array|string $operator = null, array|string $day = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('day', $column, $operator, $day, $boolean);
    }

    /**
     * Añade un where tipo month para la consulta.
     * 
     * Filtra los elementos en los que el mes coincida.
     * 
     * Example::whereMonth('created_at', 10);
     * Example::whereMonth('created_at', '<=', 12);
     * Example::whereMonth('created_at', '<=', 9, 'or');
     *
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereMonth(string $column, array|string $operator = null, int|string $month = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('month', $column, $operator, $month, $boolean);
    }
    
    /**
     * Añade un where tipo year para la consulta.
     * 
     * Filtra los elementos en los que el año coincida.
     * 
     * Example::whereYear('created_at', 2020);
     * Example::whereYear('created_at', '<=', 2021);
     * Example::whereYear('created_at', '<=', 2022, 'or');
     * 
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereYear(string $column, array|string $operator = null, int|string $year = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('year', $column, $operator, $year, $boolean);
    }

    /**
     * Añade un where tipo time para la consulta.
     * 
     * Filtra los elementos en los que la hora coincida.
     * 
     * Example::whereTime('created_at', '11:20');
     * Example::whereTime('created_at', '<=', '11:20');
     * Example::whereTime('created_at', '<=', '11:20', 'or');
     * 
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereTime(string $column, array|string $operator = null, string $time = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('time', $column, $operator, $time, $boolean);
    }

    /**
     * Añade un where tipo date para la consulta.
     * 
     * Filtra los elementos en los que la fecha coincida.
     * 
     * Example::whereDate('created_at', '2020-10-19');
     * Example::whereDate('created_at', '<=', '2022-04-14');
     * Example::whereDate('created_at', '<=', '2021-10-19', 'or');
     * 
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereDate(string $column, array|string $operator = null, string $date = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('date', $column, $operator, $date, $boolean);
    }

    /**
     * Añade un where tipo datetime para la consulta.
     * 
     * Filtra los elementos en los que la fecha y hora coincida.
     * 
     * Example::whereDateTime('created_at', '2020-10-19 11:20:00');
     * Example::whereDateTime('created_at', '<=', '2020-10-19 11:20:00');
     * Example::whereDateTime('created_at', '<=', '2020-10-19 11:20:00 ', 'or');
     * 
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereDateTime(string $column, array|string $operator = null, string $dateTime = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('datetime', $column, $operator, $dateTime, $boolean);
    }
    
    /**
     * Añade un where tipo TimeStamp para la consulta.
     * 
     * Filtra los elementos en los que la fecha y hora coincida.
     * 
     * Example::whereTimeStamp('created_at', '2020-10-19 11:20:00');
     * Example::whereTimeStamp('created_at', '<=', '2020-10-19 11:20:00');
     * Example::whereTimeStamp('created_at', '<=', '2020-10-19 11:20:00', 'or');
     * 
     * @param string       $column
     * @param array|string $operator
     * @param int|string   $day
     * @param string       $boolean
     * @return $this
     */
    protected function whereTimeStamp(string $column, array|string $operator = null, string $timeStamp = null, string $boolean = 'and') : object
    {
        return $this->whereGeneralDate('timestamp', $column, $operator, $timeStamp, $boolean);
    }


    # =============================== # 
    # =========== HELPERS =========== #
    # =============================== #

    /**
     * Función para crear where tipo day, month y year. 
     * 
     * Caso 1:
     * Example::whereDay('created_at', 21);
     * Example::whereMonth('created_at', '<=', 10);
     * Example::whereYear('created_at', '<=', 2020, 'or');
     * 
     * Caso 2:
     * Example::whereDay( 'created_at', [1, 2, 3] );
     * Example::whereDay( 'created_at', [1, 2, 3], 'or' );
     * Example::whereMonth( 'created_at', [1, 2, 3], '<=' );
     * Example::whereYear( 'created_at', [2020, 2021, 2022], '<=', 'or' );
     * 
     * @param string     $type
     * @param string     $column
     * @param array|string $operator
     * @param int|string $day
     * @param string     $boolean
     * @return $this
     */
    private function whereGeneralDate(string $type, string $column, array|string $operator = null, int|string $date = null, string $boolean = 'and') : object
    {   
        $this->where($column, $operator, $date, $boolean);

        $numValues = 1;
        if ( is_array($operator) ) {
            $numValues = count($operator);
        }

        $numsWheres = count($this->wheres);
        for ($i=1; $i <= $numValues; $i++) { 
            $this->wheres[ $numsWheres - $i ]['type'] = $type;
        }

        return $this;
    }

}