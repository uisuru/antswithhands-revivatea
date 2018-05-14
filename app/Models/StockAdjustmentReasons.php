<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class StockAdjustmentReasons extends Model
{

    protected $fillable = ['reason', 'is_default', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('stock_adjustment_reasons');

        parent::__construct($attributes);
    }

}
