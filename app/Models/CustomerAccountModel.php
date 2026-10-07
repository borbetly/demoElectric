<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerAccountModel extends Model
{
    protected $table            = 'customer_accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'account_number',
        'customer_name',
        'address',
        'phone',
        'email',
        'meter_number',
        'connection_type',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        // Required only when an update supplies {id} to the unique rule below.
        // It is not an allowed field, so it is never written to the database.
        'id'              => 'permit_empty|is_natural_no_zero',
        'account_number'  => 'required|max_length[50]|is_unique[customer_accounts.account_number,id,{id}]',
        'customer_name'   => 'required|max_length[150]',
        'address'         => 'required',
        'phone'           => 'permit_empty|max_length[20]',
        'email'           => 'permit_empty|valid_email|max_length[100]',
        'meter_number'    => 'permit_empty|max_length[50]',
        'connection_type' => 'required|in_list[residential,commercial,industrial]',
        'status'          => 'required|in_list[active,inactive,suspended]',
    ];

    protected $validationMessages = [
        'account_number' => [
            'is_unique' => 'This account number is already in use.',
        ],
    ];

    /**
     * Returns the filtered customer list using the professor-provided fields.
     * The named pagination group prevents this list from clashing with other pagers.
     */
    public function paginateAccounts(string $search, string $status, string $type, int $perPage = 10): array
    {
        if ($search !== '') {
            $this->groupStart()
                ->like('account_number', $search)
                ->orLike('customer_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        if ($status !== '') {
            $this->where('status', $status);
        }

        if ($type !== '') {
            $this->where('connection_type', $type);
        }

        return $this->orderBy('created_at', 'DESC')->paginate($perPage, 'accounts');
    }

    /**
     * Fetches dashboard statistics with fresh query builders so filters and
     * pagination never affect the totals.
     *
     * @return array{total:int,active:int,inactive:int,suspended:int}
     */
    public function accountCounts(): array
    {
        return [
            'total'     => $this->db->table($this->table)->countAllResults(),
            'active'    => $this->db->table($this->table)->where('status', 'active')->countAllResults(),
            'inactive'  => $this->db->table($this->table)->where('status', 'inactive')->countAllResults(),
            'suspended' => $this->db->table($this->table)->where('status', 'suspended')->countAllResults(),
        ];
    }
}
