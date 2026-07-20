<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table            = 'commissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'operator_id',
        'commission_percentage',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * RǸcupre toutes les commissions avec les dǸtails de l'opǸrateur associǸ.
     */
    public function getCommissionsWithOperators()
    {
        return $this->select('commissions.*, operators.name as operator_name, operators.code as operator_code')
                    ->join('operators', 'operators.id = commissions.operator_id')
                    ->findAll();
    }
}
