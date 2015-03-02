<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.2
 */

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\FilterValidator;

/**
 * The filter validator which converts character-width
 *
 * This validator is useful in Japanese.
 */
class ConvertCharacterWidthFilterValidator extends FilterValidator
{
    /** @var string Convert option. See mb_convert_kana manual. */
    public $option = 'asKV';

    /** @var string Charset that used for converting. default: Yii::$app->charset */
    public $charset;

    /** @inheritdoc */
    public function init()
    {
        if ($this->charset === null) {
            $this->charset = Yii::$app->charset;
        }
        $this->filter = function ($value) {
            return mb_convert_kana($value, $this->option, $this->charset);
        };
        parent::init();
    }
}
