<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm\Triggers;

Trait InsertTrigger
{
    /**
     * Inserta uno o multiples registros y retorna los datos de la consulta y en caso de error, lo especifica.
     *
     * @param string $output
     * @param mixed $callbak
     * @param array $args
     * @return int|false
     **/
    protected function insert( array $data = [], array|string $format = null ) : array|int|false
    {
        if ( ! $data || ! is_array($data) ) {
            return false;
        }

        $keyFirst = array_key_first($data);
        $registers = [];
        ( ! is_array( $data[$keyFirst] ) ) 
            ? $registers[] = $data
            : $registers = $data;

        $log = [];
        $log['error'] = false;
        foreach ( $registers as $key => $register ) {
            $this->insertRegister( $this->table, $register, $format );
            if ( $this->checkError() ) {
                $log[ $key ]['status'] = 'inserted';
                $log[ $key ]['status-code'] = '201';
                $log[ $key ]['data'] = $register;
            } else {
                $log['error'] = true;
                $log[ $key ]['status'] = 'error';
                $log[ $key ]['status-code'] = '400';
                $log[ $key ]['data'] = $register;
            }
        }

        return $log;
    }

    /**
     * Inserta un unico registro.
     *
     * @param string $table
     * @param array  $data
     * @param array  $format
     * @return int|false
     **/
    private function insertRegister( string $table, array $data, array $format = null ) : int|false
    {
        return $this->wpdb->insert(
            $table,
            $data,
            self::getFormat($data, $format)
        );
    }
    
    /**
     * Obtiene el formato de un array de datos.
     *
     * %d (integer)
     * %f (float)
     * %s (string)
     * 
     * @param array $data
     * @param array|null $format
     * @return type
     **/
    public static function getFormat( array $data, ?array $format ) : ?array
    {
        if ( $format ) {
            return $format;
        }

        $format = [];
        foreach ( $data as $column => $value ) {
            if ( is_integer($value) ) {
                $format[] = '%d';
            } else if ( is_float($value) ) {
                $format[] = '%f';
            } else {
                $format[] = '%s';
            }
        }
        return $format;
    }


}
