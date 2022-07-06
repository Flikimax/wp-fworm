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
     * @param array $data
     * @param array|string|null $format
     * @return array
     **/
    protected function insert( array $data = [], array|string|null $format = null ) : array
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
            \Fworm\Trigger::getFormat($data, $format)
        );
    }

}
