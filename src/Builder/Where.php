<?php
/**
 * Clausulas Where.
 * 
 */

namespace Fworm\Builder;

Trait Where
{
    // [type] => Basic | between | in | notIn | like | notLike | between | notBetween | isNull | isNotNull | isEmpty | isNotEmpty | isTrue | isFalse | is
    // [column] => post_status
    // [operator] => =
    // [value] => publish
    // [boolean] => and
    // [Not] => false|true



    public static function casa()
    {
        return 'casa';
    }


    /**
     * Añade un where básico a la consulta.
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
        // * Example::where( ['id' => 2, 'name' => 'Flikimax'] );
        // * Example::where( ['id' => 2, 'name' => 'Flikimax'], 'or' );
        // * Example::where( ['id' => 2, 'name' => 'Flikimax'], '<=' );
        // * Example::where( ['id' => 2, 'name' => 'Flikimax'], '<=', 'or' );
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
        // * Example::where( 'id', [1, 2, 3] );
        // * Example::where( 'id', [1, 2, 3], 'or' );
        // * Example::where( 'id', [1, 2, 3], '<=' );
        // * Example::where( 'id', [1, 2, 3], '<=', 'or' );
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
        // * Example::where( 'id', 2 );
        if ( is_string($column) && !$value ) {
            if ( !in_array($operator, $this->operators) ) {
                $value = $operator;
            }
            $operator = '=';
        }

        # Caso 4 (default):
        // * Example::where( 'id', '=', 2 );
        if ( !in_array($operator, $this->operators) ) {
            $operator = '=';
        }
        $boolean = $this->orAndValidate($boolean, true) ? $this->orAndValidate($boolean) : 'and';

        $this->wheres[] = compact('type', 'column', 'operator', 'value', 'boolean');
        return $this;
    }

    /**
     * Filtra los elementos de forma que el valor de la clave dada esté entre los valores dados.
     *
     * @param  string   $key
     * @param  iterable $values
     * @return static
     */
    protected function whereBetween(string $key, iterable $values, string $boolean = 'and')
    {
        $this->wheres[] = [
            'type' => 'between',
            'column' => $key,
            'values' => $values,
            'boolean' => $boolean,
            'not' => false,
        ];

        return $this;
    }

    /**
     * Filtra los elementos de forma que el valor de la clave dada no se encuentre entre los valores dados.
     *
     * @param  string  $key
     * @param  \Illuminate\Contracts\Support\Arrayable|iterable  $values
     * @return static
     */
    protected function whereNotBetween($key, $values)
    {
        $this->wheres[] = [
            'type' => 'not between',
            'column' => $key,
            'values' => $values,
            'boolean' => $boolean,
            'not' => true,
        ];

        return $this;
    }



    // public function whereType(string $type, int $count = 1)
    // {
    //     $wheres = array_reverse($this->wheres);

    //     for ($i=0; $i <= $count; $i++) { 
    //         $this->wheres[$i]['type'] = $type;
    //     }

    // }













    // if (! function_exists('data_get')) {
        /**
         * Get an item from an array or object using "dot" notation.
         *
         * @param  mixed  $target
         * @param  string|array|int|null  $key
         * @param  mixed  $default
         * @return mixed
         */
        function data_get($target, $key, $default = null)
        {
            if (is_null($key)) {
                return $target;
            }
    
            $key = is_array($key) ? $key : explode('.', $key);
    
            foreach ($key as $i => $segment) {
                unset($key[$i]);
    
                if (is_null($segment)) {
                    return $target;
                }
    
                if ($segment === '*') {
                    if ($target instanceof Collection) {
                        $target = $target->all();
                    } elseif (! is_iterable($target)) {
                        return value($default);
                    }
    
                    $result = [];
    
                    foreach ($target as $item) {
                        $result[] = data_get($item, $key);
                    }
    
                    return in_array('*', $key) ? Arr::collapse($result) : $result;
                }
    
                if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                    $target = $target[$segment];
                } elseif (is_object($target) && isset($target->{$segment})) {
                    $target = $target->{$segment};
                } else {
                    return value($default);
                }
            }
    
            return $target;
        }
    // }


    /**
     * Filter items where the value for the given key is null.
     *
     * @param  string|null  $key
     * @return static
     */
    public function whereNull($key = null)
    {
        return $this->whereStrict($key, null);
    }

    /**
     * Filter items where the value for the given key is not null.
     *
     * @param  string|null  $key
     * @return static
     */
    public function whereNotNull($key = null)
    {
        return $this->where($key, '!==', null);
    }


    // protected function whereBetween(string $column, array $values)
    // {
        
    // }

    // whereBetween
    // whereNotBetween

    // whereIn
    // whereNotIn

    // whereNull
    // whereNotNull

    // whereDate
    // whereDay('created_at', '31')
    // whereMonth('created_at', '12')
    // whereYear('created_at', '2016')
    // whereTime('created_at', '=', '11:20')
    // whereColumn('first_name', 'last_name')
    // whereColumn('updated_at', '>', 'created_at')
    // whereColumn([
    //     ['first_name', '=', 'last_name'],
    //     ['updated_at', '>', 'created_at']
    // ])




    public function orAndValidate(mixed $value, bool $check = false) : string|bool
    {
        if ( is_string($value) && (strtolower(trim($value)) === 'or' || strtolower(trim($value)) === 'and') ) {
            return ($check) ? $check : strtolower(trim($value));
        }

        return false;
    }



}
