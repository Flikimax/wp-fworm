<?php
/**
 * Modelo Posts
 * 
 */

namespace Fworm\BaseModels;

use Fworm\MainModel as Model;

class Pruebas extends Model
{
    protected string $primaryKey = 'Id';
    protected string $prefix = 'fkm_';

    protected array $columns = [
        'Id' => 'ID',
        'name' => 'Nombre',
    ];

    public int $limit = 3;

}
