<?php
/**
 * Modelo Principal. 
 * 
 */

namespace Fworm;

abstract class MainModel 
{
    use \Fworm\MainModel\General,
        \Fworm\MainModel\Union,
        \Fworm\MainModel\Select,
        \Fworm\MainModel\Where\Where,
        \Fworm\MainModel\Limit,
        \Fworm\MainModel\Offset,
        \Fworm\Builder\Query,
        \Fworm\Trigger,
        \Fworm\Properties;

    /**
     * Constructor del modelo.
     */
    public function __construct() 
    {
        try {
            $this->setProperties();
        } catch (\Exception $exception) { ?>
            <!-- <p>
                <strong>Captured exception: </strong> <?=$exception->getMessage(); ?>
            </p> -->
            <script>
                console.error( `Error (<?=WP_FWORM_NAME; ?>): <?=$exception->getMessage(); ?>` );
            </script> <?php
        }
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

    /**
     * Maneja las llamadas a las propiedades del modelo.
     * 
     * @param  string $property
     * @return mixed
     */
    public function __get( string $property ) : mixed
    {
        return ( property_exists($this, $property) ) ? $this->$property : null;
    }

}
