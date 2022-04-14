<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\MainModel\Where;

Trait Where
{
    use WhereDate;


    /**
     * Añade un where básico para la consulta.
     *
     * Caso 1:
     * Example::where( ['id' => 2, 'name' => 'Flikimax'] );
     * Example::where( ['id' => 2, 'name' => 'Flikimax'], 'or' );
     * Example::where( ['id' => 2, 'name' => 'Flikimax'], '<=' );
     * Example::where( ['id' => 2, 'name' => 'Flikimax'], '<=', 'or' );
     * 
     * Caso 2:
     * Example::where( 'id', [1, 2, 3] );
     * Example::where( 'id', [1, 2, 3], 'or' );
     * Example::where( 'id', [1, 2, 3], '<=' );
     * Example::where( 'id', [1, 2, 3], '<=', 'or' );
     * 
     * Caso 3:
     * Example::where( 'id', 2 );
     * 
     * Caso 4 (default):
     * Example::where( 'id', '=', 2 );
     * Example::where( 'id', '=', 2, 'or' );
     * Example::where( 'id', '=', 2, 'and' );
     * 
     * Encadenamiento.
     * Example::where( 'id', '2')->where('name', '=', 'Flikimax' );
     * 
     * @param  string|array $column
     * @param  mixed  $operator -> public array $operators
     * @param  mixed  $value
     * @param  string $boolean
     * @return $this
     */
    protected function where(string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
    {
        $type = 'basic';
        // Si la columna es un array, asumiremos que es un array de pares clave-valor
        // y podemos añadir cada uno de ellos como una cláusula where. Mantendremos el booleano que
        // recibido cuando el método fue llamado y lo pasaremos al where anidado.

        # Caso 1:
        if ( is_array($column) ) {
            if ( $operator && $this->orAndValidate($operator, true) ) {
                $boolean = $operator;
                $operator = '=';
            } else if ( $operator && in_array($operator, $this->operators) ) {
                $operator = $operator;
            } else {
                $operator = '=';
            }

            if ( $value && $this->orAndValidate($value, true) ) {
                $boolean = $value;
            }

            foreach ( $column as $col => $val ) {
                $this->where($col, $operator, $val, $boolean);
            }

            return $this;
        }

        # Caso 2:
        if ( is_string($column) && is_array($operator) ) {
            $opt = '=';
            if ( $value && $this->orAndValidate($value, true) ) {
                $boolean = $value;
            } else if ( $value && in_array($value, $this->operators) ) {
                $opt = $value;
            }

            $boolean = $this->orAndValidate($boolean, true) ? $this->orAndValidate($boolean) : 'and';

            foreach ( $operator as $value ) {
                $this->where($column, $opt, $value, $boolean);
            }

            return $this;
        }

        # Caso 3:
        if ( is_string($column) && !$value ) {
            if ( !in_array($operator, $this->operators) ) {
                $value = $operator;
            }
            $operator = '=';
        }

        # Caso 4 (default):
        if ( !in_array($operator, $this->operators) ) {
            $operator = '=';
        }
        $boolean = $this->orAndValidate($boolean, true) ? $this->orAndValidate($boolean) : 'and';

        $this->wheres[] = compact('type', 'column', 'operator', 'value', 'boolean');
        return $this;
    }


    # =============================== # 
    # =========== BETWEEN =========== #
    # =============================== # 

    /**
     * Añade un where tipo between para la consulta.
     * 
     * Filtra los elementos de forma que el valor de la clave dada esté entre los valores dados.
     *
     * @param  string $key
     * @param  array  $values
     * @param  string $boolean
     * @return $this
     */
    protected function whereBetween(string $key, array $values, string $boolean = 'and')
    {
        $count = count($values);
        if ( $count < 2 ) {
            return $this;
        } else if ( $count > 2 ) {
            # Solo se toman los primeros 2 elementos.
            $values = array_slice($values, 0, 2);
        }
        
        $boolean = $this->orAndValidate($boolean, true) ? $this->orAndValidate($boolean) : 'and';

        $this->wheres[] = [
            'type'    => 'between',
            'column'  => $key,
            'values'  => $values,
            'boolean' => $boolean,
            'not'     => false,
        ];

        return $this;
    }

    /**
     * Añade un where tipo not between para la consulta.
     * 
     * Filtra los elementos de forma que el valor de la clave dada no se encuentre entre los valores dados.
     *
     * @param  string $key
     * @param  array  $values
     * @param  string $boolean
     * @return $this
     */
    protected function whereNotBetween(string $key, array $values, string $boolean = 'and')
    {
        $this->whereBetween($key, $values, $boolean);
        $this->wheres[ count($this->wheres) - 1 ]['not'] = true;

        return $this;
    }

    # ============================ # 
    # =========== NULL =========== #
    # ============================ # 

    /**
     * Añade un where tipo null para la consulta.
     * 
     * Filtra los elementos en los que el valor de la clave dada es nulo.
     *
     * @param string $column
     * @param string $boolean
     * @return $this
     */
    protected function whereNull(string $column, string $boolean = 'and') : object
    {
        $boolean = $this->orAndValidate($boolean, true) ? $this->orAndValidate($boolean) : 'and';
        $this->wheres[] = [
            'type'    => 'null',
            'column'  => $column,
            'boolean' => $boolean,
            'not'     => false,
        ];

        return $this;
    }

    /**
     * Añade un where tipo not null para la consulta.
     * 
     * Filtra los elementos en los que el valor de la clave dada no es nulo.
     *
     * @param string $column
     * @param string $boolean
     * @return $this
     */
    protected function whereNotNull(string $column, string $boolean = 'and')
    {
        $this->whereNull($column, $boolean);
        $this->wheres[ count($this->wheres) - 1 ]['not'] = true;

        return $this;
    }

    # ========================== # 
    # =========== IN =========== #
    # ========================== # 

    /**
     * Añade un where tipo in para la consulta.
     * 
     * Filtra los elementos en los que los valores coincidan.
     *
     * @param string $column
     * @param array  $values
     * @param string $boolean
     * @return $this
     */
    protected function whereIn(string $column, array $values, string $boolean = 'and') : object
    {
        $boolean = $this->orAndValidate($boolean, true) ? $this->orAndValidate($boolean) : 'and';

        $this->wheres[] = [
            'type'    => 'in',
            'column'  => $column,
            'values'  => $values,
            'boolean' => $boolean,
            'not'     => false,
        ];

        return $this;
    }

    /**
     * Añade un where tipo not in para la consulta.
     * 
     * Filtra los elementos en los que los valores no coincidan.
     *
     * @param  string|null  $key
     * @return static
     */
    protected function whereNotIn(string $column, array $values, string $boolean = 'and')
    {
        $this->whereIn($column, $values, $boolean);
        $this->wheres[ count($this->wheres) - 1 ]['not'] = true;

        return $this;
    }

    







    
    # ============================== # 
    # =========== Column =========== #
    # ============================== # 
    // whereColumn('first_name', 'last_name')
    // whereColumn('updated_at', '>', 'created_at')
    // whereColumn([
    //     ['first_name', '=', 'last_name'],
    //     ['updated_at', '>', 'created_at']
    // ])




    



    # =============================== # 
    # =========== HELPERS =========== #
    # =============================== #

    /**
     * Valida si un valor es OR o AND.
     *
     * @param mixed $value Valor a validar.
     * @param bool $check
     * @return string|bool
     **/
    public function orAndValidate(mixed $value, bool $check = false) : string|bool
    {
        if ( is_string($value) && (strtolower(trim($value)) === 'or' || strtolower(trim($value)) === 'and') ) {
            return ($check) ? $check : strtolower(trim($value));
        }

        return false;
    }

}
