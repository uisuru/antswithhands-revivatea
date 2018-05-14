<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Grn extends Model
{

    protected $fillable = ['grn_number','description', 'warehouse_id', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('grn');

        parent::__construct($attributes);
    }

    public function grn_items()
    {
        return $this->hasMany('App\Models\GrnItems', 'grn_id', 'id');
    }

    public function warehouse()
    {
        return $this->hasMany('App\Models\Warehouse', 'warehouse_id', 'id');
    }

}
