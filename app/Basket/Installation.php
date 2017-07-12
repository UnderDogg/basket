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
use PayBreak\Foundation\Properties\Bitwise;

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
 * @property bool   $ext_feature_merchant_liable
 * @property        $created_at
 * @property        $updated_at
 * @property Merchant $merchant
 * @property string $location_instruction
 * @property int    $validity
 * @property string $custom_logo_url
 * @property string $disclosure
 * @property int    $finance_offers
 * @property string $default_template_footer
 * @property string $email_configuration
 * @property int    $merchant_payments
 * @property Template[] $templates
 * @package App\Basket
 */
class Installation extends Model
{
    protected $table = 'installations';

    const IN_STORE = 2;
    const LINK = 4;
    const EMAIL = 8;

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
        'ext_feature_merchant_liable',
        'location_instruction',
        'validity',
        'custom_logo_url',
        'disclosure',
        'finance_offers',
        'default_template_footer',
        'merchant_payments',
        'email_configuration',
    ];

    /**
     * @author WN
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(\App\Basket\Merchant::class);
    }

    /**
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(\App\Basket\Location::class);
    }

    /**
    * @author EB
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function templates()
    {
        return $this->belongsToMany(\App\Basket\Template::class);
    }

    /**
     * @author EB
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productLimits()
    {
        return $this->hasMany(\App\Basket\ProductLimit::class);
    }

    /**
     * @author EB
     * @return $this
     * @throws Exception
     */
    public function deactivate()
    {
        if (!$this->exists) {
            throw new Exception('Trying to deactivate none existing Installation');
        }
        $this->active = false;

        if ($this->save()) {
            foreach ($this->locations()->get() as $loc) {
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
        if (!$this->exists) {
            throw new Exception('Trying to activate none existing Installation');
        }

        if (!$this->merchant()->get()[0]->active) {
            throw new Exception('Can\'t activate Installation because Merchant is not active.');
        }

        $this->active = true;

        if ($this->save()) {
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
     * Returning HTML for Parsed Markdown
     *
     * @author EB
     * @return string
     */
    public function getDefaultTemplateFooterAsHtml()
    {
        return ((new \Parsedown())->text(htmlspecialchars($this->default_template_footer)));
    }

    /**
     * Return an array of Finance Offers (from bitwise stored)
     *
     * @author EB
     * @return array
     */
    public function getBitwiseFinanceOffers()
    {
        $financeOffers = Bitwise::make($this->finance_offers);

        return [
            'in_store' => [
                'value' => self::IN_STORE,
                'active' => $financeOffers->contains(self::IN_STORE),
                'text' => 'Continue with In-store Application',
            ],
            'link' => [
                'value' => self::LINK,
                'active' => $financeOffers->contains(self::LINK),
                'text' => 'Create Application Link',
                'name' => 'link',
            ],
            'email' => [
                'value' => self::EMAIL,
                'active' => $financeOffers->contains(self::EMAIL),
                'text' => 'Email Application',
                'name' => 'email',
            ]
        ];
    }
}
