<?php
/**
 * Propiedades para el Model.
 * 
 */

namespace Fworm;

Trait Properties
{
    // /**
    //  * Atributos propios del modelo.
    //  * 
    //  * @var object
    //  */
    // protected object $attributes;

    // /**
    //  * Propiedades definidas por el dev.
    //  * 
    //  * @var array
    //  */
    // protected array $data = [];




    // /**
    //  * Usar el prefijo de las tablas.
    //  * 
    //  * @var bool|string
    //  */
    // public bool|string $prefix = true;



    
    /**
     * Infomración en Raw (bruto) de las columnas del modelo.
     * 
     * @var array
     */
    public array $columnsRaw;

    /**
     * Vinculaciones de valores de consulta actuales.
     * 
     * @var array
     */
    public array $bindings = [
        'select' => [],
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'order' => [],
        'union' => [],
        'unionOrder' => [],
    ];

    /**
     * Las columnas que deben ser devueltas.
     * 
     * @var array
     */
    public array $columns;
    
    /**
     * Nombre para las columnas que deben ser devueltas.
     * 
     * @var object
     */
    public object $columnsAs;



    /**
     * La tabla a la que se dirige la consulta.
     * 
     * @var string
     */
    public string $table;

    /**
     * La tabla joins para la consulta.
     *
     * @var array
     */
    public array $joins;

    /**
     * Las restricciones where de la consulta.
     * 
     * @var array
     */
    public array $wheres = [];

    /**
     * Las agrupaciones para la consulta.
     * 
     * @var array
     */
    public array $groups;

    /**
     * Las restricciones que tiene la consulta.
     * 
     * @var array
     */
    public array $havings;

    /**
     * La ordenación de la consulta.
     * 
     * @var array
     */
    public array $orders;

    /**
     * El número máximo de registros a devolver.
     * 
     * @var int
     */
    public int $limit;

    /**
     * El número de registros a omitir.
     * 
     * @var int
     */
    public int $offset;

    /**
     * Las declaraciones de unión de la consulta.
     * 
     * @var array
     */
    public array $unions;

    /**
     * El número máximo de registros de la unión a devolver.
     * 
     * @var int
     */
    public int $unionLimit;

    /**
     * El número de registros de la unión que hay que omitir.
     * 
     * @var int
     */
    public int $unionOffset;

    /**
     * Las ordenaciones para la consulta de la unión.
     * 
     * @var array
     */
    public array $unionOrders;

    /**
     * Las llamadas de retorno que deben ser invocadas antes de que se ejecute la consulta.
     * 
     * @var array
     */
    public array $beforeQueryCallbacks = [];

    /**
     * Todos los operadores de cláusulas disponibles.
     * = : Igual
     * < : Menor que
     * > : Mayor que
     * <= : Menor o igual que
     * >= : Mayor o igual que
     * <> : Diferente de
     * != : Diferente de
     * <=> : Igual o diferente de
     * LIKE : Contiene
     * NOT LIKE : No contiene
     * 
     * 
     * IN : Está en
     * NOT IN : No está en
     * BETWEEN : Está entre
     * NOT BETWEEN : No está entre
     * IS NULL : Es nulo
     * IS NOT NULL : No es nulo
     * 
     * @var string[]
     */
    public array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * Todos los operadores disponibles a nivel de bits.
     * 
     * @var string[]
     */
    public array $bitwiseOperators = [
        '&', '|', '^', '<<', '>>', '&~',
    ];

    /**
     * Query a ejecutar.
     * 
     * @var string
     */
    public string $query = '';



    // public function __set($propiedad, $valor){

    //     if ( $propiedad != 'wpdb' ) {

    //         if (  ) {

    //         }

    //         echo "<pre>propiedad: ";
    //         print_r( $propiedad );
    //         echo "</pre>";
    
    //         echo "<pre>valor: ";
    //         print_r( $valor );
    //         echo "</pre>";

    //     }


    //     return $this->$propiedad = $valor;
    // }

    // public function __get($propiedad){
    //     // if (property_exists($this, $propiedad)) {
    //         return $this->$propiedad;
    //     // }
    // }


}


