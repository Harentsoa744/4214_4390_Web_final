<?php

namespace App\Models;

use CodeIgniter\Model;

class SettlementModel extends Model
{
    protected $table            = 'settlements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'destination_operator_id', 'period_start', 'period_end', 
        'total_transfer_amount', 'total_commission', 'amount_to_settle', 
        'amount_settled', 'status', 'settled_at', 'reference'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';
}
