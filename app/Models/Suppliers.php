<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Suppliers extends Model
{

    protected $fillable = ['name', 'address','city', 'phone_number', 'contact_person', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('suppliers');

        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'supplier_id', 'id');
    }
}
