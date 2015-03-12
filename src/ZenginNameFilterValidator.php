<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.1.0
 */

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\FilterValidator;

/**
 * The filter validator which converts to ZENGIN format
 */
class ZenginNameFilterValidator extends FilterValidator
{
    /** @var string Charset that used for converting. default: Yii::$app->charset */
    public $charset;

    /** @inheritdoc */
    public function init()
    {
        if ($this->charset === null) {
            $this->charset = Yii::$app->charset;
        }
        $this->filter = function ($value) {
            $utf8 = mb_convert_encoding($value, 'UTF-8', $this->charset);
            $utf8 = mb_convert_kana($utf8, 'askh', 'UTF-8');
            $utf8 = strtoupper($utf8);
            $utf8 = mb_str_replace(
                ['ｰ', '･', 'ｧ', 'ｨ', 'ｩ', 'ｪ', 'ｫ', 'ｯ', 'ｬ', 'ｭ', 'ｮ', '￥'],
                ['-', '.', 'ｱ', 'ｲ', 'ｳ', 'ｴ', 'ｵ', 'ﾂ', 'ﾔ', 'ﾕ', 'ﾖ', '\\'],
                $utf8,
                'UTF-8'
            );
            return mb_convert_encoding(trim($utf8), $this->charset, 'UTF-8');
        };
        parent::init();
    }
}
