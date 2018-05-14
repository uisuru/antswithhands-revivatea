<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class InvoiceItems extends Model
{

    protected $fillable = ['invoice_id', 'item_id','qty','package_id','free_items',
        'unit_price','total_price','discount_rate','discount_amount','net_price', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('invoice_items');

        parent::__construct($attributes);
    }

}
