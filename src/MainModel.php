<?php
/**
 * Modelo Principal. 
 * 
 */

namespace Fworm;

abstract class MainModel 
{
    use \Fworm\MainModel\Where\Where,
        \Fworm\Builder\Query,
        \Fworm\Trigger,
        \Fworm\Properties;

    /**
     * Constructor del modelo.
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb  = $wpdb;
        # Seran gestiados por los disparadores (Trigger).
        // $this->wpdb->show_errors = false;

        # Definicion de tabla
        $prefix = '';
        if ( property_exists($this, 'prefix') ) {
            if ( is_string($this->prefix) ) {
                $prefix = $this->prefix;
            } else if ( $this->prefix === true ) {
                $prefix = $this->wpdb->prefix;
            }
        } else {
            $prefix = $this->wpdb->prefix;
        }
        $this->table = $prefix . strtolower(
            preg_replace(
                "([A-Z])", 
                "_$0",
                lcfirst( 
                    basename(static::class)
                )
            )
        );
        # Definicion de tabla
        



        $columnsRaw = $this->wpdb->get_results(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}'", 
        );

        $attributes = [];
        $columnsAs = []; # Usar
        foreach ( $columnsRaw as $column ) {
            $attributes[ $column->COLUMN_NAME ] = null;
            $columnsAs[ $column->COLUMN_NAME ] = $column->COLUMN_NAME;
            $this->columnsRaw[ $column->COLUMN_NAME ] = $column;
        }
        $this->attributes = (object) $attributes;
        $this->columnsAs = (object) $columnsAs;
    }

    /**
     * Maneja las llamadas a los métodos dinámicos del modelo.
     * 
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        try {
            if ( method_exists($this, $method) ) {
                return call_user_func_array(
                    [$this, $method], 
                    $parameters
                );
            } else if ( WP_DEBUG ) {
                throw new \Exception( "The '{$method}' method does not exist." );
            }
        } catch (\Exception $exception) { ?>
            <!-- <p>
                <strong>Captured exception: </strong> <?=$exception->getMessage(); ?>
            </p> -->
            <script>
                console.error( `Error (<?=WP_FWORM_NAME; ?>): <?=$exception->getMessage(); ?>` );
            </script> <?php
        }

        return $this;
    }

    /**
     * Maneja las llamadas a métodos dinámicos estáticos del modelo.
     * 
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

}
