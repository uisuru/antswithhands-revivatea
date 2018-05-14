<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Brands extends Model
{

    protected $fillable = ['type', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('brands');

        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->hasOne('App\Models\Brands', 'brand_id', 'id');
    }
}
