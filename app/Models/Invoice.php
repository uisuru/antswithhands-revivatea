<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Invoice extends Model
{

    protected $fillable = ['customer_id', 'invoice_date','gross_amount',
        'discount','tax_amount','net_amount','free_items','sales_rep', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('invoice');

        parent::__construct($attributes);
    }

    public function customers()
    {
        return $this->belongsTo('App\Models\Company', 'customer_id', 'id');
    }

}
