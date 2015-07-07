<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class role_permissions extends Model  {

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
    protected $fillable = [''];

}
