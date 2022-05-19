<?php
/**
 * Modelo Posts
 * 
 */

namespace Fworm\BaseModels;

use Fworm\MainModel as Model;

class Posts extends Model
{
    public string $primaryKey = 'ID';

    protected array $columns = [
        'ID' => 'id',
        'post_title' => 'Título',
    ];
}
