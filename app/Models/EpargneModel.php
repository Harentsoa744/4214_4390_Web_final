<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table            = 'epargne';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    //     epargne_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.0,
    // total_amount DECIMAL(15, 2) DEFAULT 0.00,
    protected $allowedFields    = [
        'id',
        'client_id',
        'epargne_percentage',
        'total_amount',
        'created_at',
        'updated_at'
    ];

        //     id INTEGER PRIMARY KEY AUTOINCREMENT,
    // client_id INTEGER NOT NULL,
    // total_amount DECIMAL(15, 2) DEFAULT 0.00,
    // created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    // updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    // FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * RǸcupre toutes les commissions avec les dǸtails de l'opǸrateur associǸ.
     */
}
