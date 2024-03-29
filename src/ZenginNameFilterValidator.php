<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.1.0
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\FilterValidator;

use function assert;
use function is_string;
use function mb_convert_encoding;
use function mb_convert_kana;
use function mb_str_replace;
use function strtoupper;
use function trim;

/**
 * The filter validator which converts to ZENGIN format
 */
class ZenginNameFilterValidator extends FilterValidator
{
    /** @var string Charset that used for converting. default: Yii::$app->charset */
    public ?string $charset = null;

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        if ($this->charset === null) {
            $this->charset = Yii::$app->charset;
        }

        $this->filter = function ($value) {
            $value = (string)$value;
            $utf8 = (string)mb_convert_encoding($value, 'UTF-8', $this->charset ?? 'UTF-8');
            $utf8 = (string)mb_convert_kana($utf8, 'askh', 'UTF-8');
            $utf8 = (string)strtoupper($utf8);
            $utf8 = mb_str_replace(
                ['ｰ', '･', 'ｧ', 'ｨ', 'ｩ', 'ｪ', 'ｫ', 'ｯ', 'ｬ', 'ｭ', 'ｮ', '￥'],
                ['-', '.', 'ｱ', 'ｲ', 'ｳ', 'ｴ', 'ｵ', 'ﾂ', 'ﾔ', 'ﾕ', 'ﾖ', '\\'],
                $utf8,
                'UTF-8',
            );
            assert(is_string($utf8));

            return mb_convert_encoding(
                trim($utf8),
                $this->charset ?? 'UTF-8',
                'UTF-8',
            );
        };
        parent::init();
    }
}
