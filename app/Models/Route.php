<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Route extends Model
{

    protected $fillable = ['route_name', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('route');

        parent::__construct($attributes);
    }

    public function company()
    {
        return $this->hasOne('App\Models\Route', 'route_id', 'id');
    }
}
