<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_role';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['permission_id', 'role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['permission_id', 'role_id'];
}
