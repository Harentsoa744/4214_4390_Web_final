<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferBatchModel extends Model
{
    protected $table            = 'transfer_batches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sender_client_id', 'total_amount', 'total_fee', 
        'total_commission', 'include_withdrawal_fee', 'status'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';
}
