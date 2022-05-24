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

    # Opcional.
    // public int $limit = 1;
    # Opcional.
    // public int $offset = 3;
}
