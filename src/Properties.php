<?php
/**
 * Propiedades para el Model.
 * 
 */

namespace Fworm;

Trait Properties
{
    /**
     * La tabla a la que se dirige la consulta.
     * 
     * @var string
     */
    protected string $table = '';

    /**
     * Nombre de la columna que tenga la llave primaria de la tabla.
     * @var string
     */
    protected string $primaryKey = '';

    /**
     * Prefijo de la tabla.
     * @var string
     */
    protected string $prefix = '';

    /**
     * Infomración en Raw (bruto) de las columnas del modelo.
     * 
     * @var array
     */
    protected array $columnsRaw;

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
    protected array $columns = [];

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
    public int $limit = 0;

    /**
     * El número de registros a omitir.
     * 
     * @var int
     */
    public int $offset = 0;

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



    


    /**
     * Estable propiedades del modelo.
     *
     * @return void
     **/
    protected function setProperties() : void
    {
        $this->setWpdb();
        $this->setTable();
        $this->setColumnsRaw();
        $this->setPrimaryKey();
        $this->setOutput();
    }

    /**
     * Establece WPDB y algunas propiedades.
     *
     * @return void
     * @throws !$wpdb
     **/
    private function setWpdb() : void
    {
        global $wpdb;
        if ( !$wpdb ) {
            throw new \Exception( 'The global variable $wpdb is not defined' );
        }

        $this->wpdb = $wpdb;
        # Los errores seran gestiados por los disparadores (Trigger).
        $this->wpdb->show_errors = false;
    }

    /**
     * Establece los datos de las columnas de la tabla.
     *
     * @return void
     **/
    private function setColumnsRaw() : void
    {
        $this->columnsRaw = $this->wpdb->get_results(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}'", 
        );

        if ( !$this->columnsRaw ) {
            throw new \Exception( "No se encontró la base de datos INFORMATION_SCHEMA, la tabla COLUMNS o la tabla {$this->table}." );
        }
    }

    /**
     * Estable el nombre de la tabla.
     *
     * @return void
     **/
    private function setTable() : void
    {
        # Prefijo de la tabla.
        $prefix = $this->wpdb->prefix;
        if ( !empty($this->prefix) && is_string($this->prefix) ) {
            $prefix = $this->prefix;
        }

        # Nombre de la tabla.
        if ( !empty($this->table) ) {
            $this->table = $prefix . $this->table;
        } else {
            $this->table = $prefix . strtolower(
                preg_replace(
                    "([A-Z])", 
                    "_$0",
                    lcfirst( 
                        basename(static::class)
                    )
                )
            );
        }
    }

    /**
     * Estable la Primary Key.
     *
     * @return void.
     **/
    private function setPrimaryKey() : void
    {
        $primaryKey = null;
        if ( empty($this->primaryKey) ) {
            $primaryKey = $this->primaryKey;
        }

        foreach ($this->columnsRaw as $column) {
            if ( !$primaryKey && $column->COLUMN_NAME == $primaryKey ) {
                break;
            } else if ( $column->COLUMN_KEY === 'PRI' ) {
                $primaryKey = $column->COLUMN_NAME;
                break;
            }
        }

        if ( !$primaryKey ) {
            throw new \Exception( "No se encontró la Primary Key '{$this->primaryKey}' en la tabla {$this->table}." );
        } else {
            $this->primaryKey = $primaryKey;
        }
    }

    /**
     * Estable el output (salida) para las consultas.
     *
     * @return void
     **/
    private function setOutput() : void
    {
        if ( empty($this->output) || !in_array($this->output, $this->outputs) ) {
            $this->output = 'OBJECT_K';
        }
    }

}


