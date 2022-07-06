<?php
/**
 * Disparadores del modelo.
 * 
 */

namespace Fworm\Triggers;

Trait DeleteTrigger
{
    /**
     * Elimina un registro y retorna información en caso de error.
     *
     * @param array $where
     * @param array|string|null $whereFormat
     * @return array
     **/
    protected function delete( array $where = [], array|string|null $whereFormat = null ) : array
    {
        $validation = (bool) $this->wpdb->delete(
            $this->table,
            $where,
            \Fworm\Trigger::getFormat($where, $whereFormat)
        );
        
        $log = [];
        $log['error'] = false;
        if ( $validation === false ) {
            $log['error'] = true;
            $log['status'] = 'error';
            $log['status-code'] = '400';
        } else {
            $log['status'] = 'deleted';
            $log['status-code'] = '200';
        }

        return $log;
    }


}
