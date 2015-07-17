<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Installations extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'installations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'merchant_id', 'active', 'linked', 'ext_id', 'ext_name', 'ext_return_url', 'ext_notification_url', 'ext_default_product'];

}
