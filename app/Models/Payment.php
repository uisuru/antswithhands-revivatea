<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Payment extends Model
{

    protected $fillable = ['invoice_id','invoice_number','payment_code','paid_amount','cheque_number','bank_code','branch_code', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('payment');

        parent::__construct($attributes);
    }

}
