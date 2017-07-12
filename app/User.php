<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * User
 *
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int    $merchant_id
 * @property string $remember_token
 * @property        $created_at
 * @property        $updated_at
 * @property Collection $roles
 * @property Collection $locations
 *
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'merchant_id', 'role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the merchant record associated with the user.
     * @author MS
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(\App\Basket\Merchant::class);
    }

    /**
     * Get the role record associated with the user.
     *
     * TODO To remove! ~WN
     *
     * @deprecated
     * @author MS
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(\App\Role::class);
    }

    /**
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function locations()
    {
        return $this->belongsToMany(\App\Basket\Location::class);
    }
}
