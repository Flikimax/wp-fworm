<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm\Triggers;

Trait UpdateTrigger
{
    /**
     * Actualiza un registro y retorna los datos de la consulta y en caso de error, lo especifica.
     *
     * @param array $data
     * @param array $where
     * @param array|string|null $format
     * @param array|string|null $whereFormat
     * @return array
     **/
    protected function update( array $data = [], array $where = [], array|string|null $format = null, array|string|null $whereFormat = null ) : array
    {
        if ( ! $data || ! is_array($data) ) {
            return false;
        }

        $validation = (bool) $this->wpdb->update(
            $this->table,
            $data,
            $where,
            \Fworm\Trigger::getFormat($data, $format),
            \Fworm\Trigger::getFormat($where, $whereFormat)
        );
        
        $log = [];
        $log['error'] = false;
        if ( $validation === false ) {
            $log['error'] = true;
            $log['status'] = 'error';
            $log['status-code'] = '400';
        } else {
            $log['status'] = 'updated';
            $log['status-code'] = '201';
        }
        $log['data'] = $data;

        return $log;
    }


}
