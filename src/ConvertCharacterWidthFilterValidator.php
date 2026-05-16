<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.2
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Override;
use jp3cki\yii2\validators\internal\AppHelper;
use yii\validators\FilterValidator;

use function is_scalar;
use function mb_convert_kana;

/**
 * The filter validator which converts character-width
 *
 * This validator is useful in Japanese.
 */
class ConvertCharacterWidthFilterValidator extends FilterValidator
{
    /** @var string Convert option. See mb_convert_kana manual. */
    public string $option = 'asKV';

    /** @var string|null Charset that used for converting. default: Yii::$app->charset */
    public ?string $charset = null;

    /**
     * @inheritdoc
     * @return void
     */
    #[Override]
    public function init()
    {
        if ($this->charset === null) {
            $this->charset = AppHelper::app()->charset;
        }
        $this->filter = function ($value) {
            if (!is_scalar($value)) {
                return $value;
            }
            return mb_convert_kana((string)$value, $this->option, $this->charset);
        };
        parent::init();
    }
}
