<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Tax extends Model
{

    protected $fillable = ['name', 'code','rate', 'is_default', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('tax_types');

        parent::__construct($attributes);
    }

    public function company()
    {
        return $this->hasMany('App\Models\Company', 'default_tax_type_id', 'id');
    }
}
