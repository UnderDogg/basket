<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;
use Zizaco\Entrust\Traits\EntrustPermissionTrait;

class Permission extends EntrustPermission
{
    use EntrustPermissionTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
