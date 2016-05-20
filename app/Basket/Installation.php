<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Basket;

use App\Exceptions\Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Installation Model
 *
 * @author WN
 * @property int    $id
 * @property int    $merchant_id
 * @property string $name
 * @property bool   $linked
 * @property bool   $active
 * @property string $ext_id
 * @property string $ext_name
 * @property string $ext_return_url
 * @property string $ext_notification_url
 * @property string $ext_default_product
 * @property        $created_at
 * @property        $updated_at
 * @property Merchant $merchant
 * @property string $location_instruction
 * @property int    $validity
 * @property string $custom_logo_url
 * @property string $disclosure
 * @property int    $finance_offers
 * @package App\Basket
 */
class Installation extends Model
{
    protected $table = 'installations';

    const IN_STORE = 2;
    const LINK = 4;

    protected $in_store = 2;
    protected $link = 4;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'merchant_id',
        'linked',
        'ext_id',
        'ext_name',
        'ext_return_url',
        'ext_notification_url',
        'ext_default_product',
        'location_instruction',
        'validity',
        'custom_logo_url',
        'disclosure',
        'finance_offers'
    ];

    /**
     * @author WN
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo('App\Basket\Merchant');
    }

    /**
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany('App\Basket\Location');
    }

    /**
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function deactivate()
    {
        if(!$this->exists) {
            throw new Exception('Trying to deactivate none existing Installation');
        }
        $this->active = false;

        if ($this->save()) {
            foreach($this->locations()->get() as $loc) {
                $loc->deactivate();
            }
            return $this;
        }

        throw new Exception('Problem saving details');
    }

    /**
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function activate()
    {
        if(!$this->exists) {
            throw new Exception('Trying to activate none existing Installation');
        }

        if (!$this->merchant()->get()[0]->active) {
            throw new Exception('Can\'t activate Installation because Merchant is not active.');
        }

        $this->active = true;

        if($this->save()) {
            return $this;
        }

        throw new Exception('Problem saving details');
    }

    /**
     * Returning HTML for Parsed Markdown
     *
     * @author WN
     * @return string
     */
    public function getLocationInstructionAsHtml()
    {
        return ((new \Parsedown())->text(htmlspecialchars($this->location_instruction)));
    }

    /**
     * Returning HTML for Parsed Markdown
     *
     * @author WN
     * @return string
     */
    public function getDisclosureAsHtml()
    {
        return ((new \Parsedown())->text(htmlspecialchars($this->disclosure)));
    }

    /**
     * Return an array of Finance Offers (from bitwise stored)
     *
     * @author EB
     * @return array
     */
    public function getBitwiseFinanceOffers()
    {
        return [
            'in_store' => [
                'value' => self::IN_STORE,
                'active' => $this->finance_offers&self::IN_STORE,
                'text' => 'Continue with In-store Application',
            ],
            'link' => [
                'value' => self::LINK,
                'active' => $this->finance_offers&self::LINK,
                'text' => 'Continue with Application Link',
                'name' => 'link',
            ],
        ];
    }
}
