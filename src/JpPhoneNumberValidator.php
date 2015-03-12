<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\Validator;

/**
 * Validate Phone number (JAPAN spec)
 */
class JpPhoneNumberValidator extends Validator
{
    /** 固定電話 */
    const FLAG_LANDLINE     = 0x0001;
    /** 携帯電話 */
    const FLAG_MOBILE       = 0x0002;
    /** IP電話(050) */
    const FLAG_IP_PHONE     = 0x0004;
    /** フリーダイヤル(0120) */
    const FLAG_FREE_DIAL    = 0x0008;
    /** フリーアクセス(0800) */
    const FLAG_FREE_ACCESS  = 0x0010;
    /** ナビダイヤル(0570) */
    const FLAG_NAV_DIAL     = 0x0020;
    /** ダイヤルQ2(0990) */
    const FLAG_DIAL_Q2      = 0x0040;
    /** ポケベル(020) */
    const FLAG_PAGER        = 0x0080;

    /** 一般的な番号の組み合わせ */
    const FLAG_CONSUMERS    = 0x0007;
    /** すべての組み合わせ */
    const FLAG_ALL          = 0x00ff;

    /** @var int validとみなす電話番号の種類(FLAG_*の組み合わせ) */
    public $types = self::FLAG_CONSUMERS;

    /**
     * ハイフンの許可
     *
     * @var bool|null null=気にしない, true=要求, false=許可しない
     */
    public $hyphen = null;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} is not a valid phone number.');
        }
    }

    /** @inheritdoc */
    public function validateAttribute($model, $attribute)
    {
        if (!$this->isValid($model->$attribute)) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /** @inheritdoc */
    protected function validateValue($value)
    {
        if (!$this->isValid($value)) {
            return [$this->message, []];
        }
        return null;
    }

    private function isValid($number)
    {
        $classMap = [
            self::FLAG_MOBILE       => 'jp3cki\yii2\validators\internal\jpphone\Mobi',
            self::FLAG_IP_PHONE     => 'jp3cki\yii2\validators\internal\jpphone\Ip',
            self::FLAG_FREE_DIAL    => 'jp3cki\yii2\validators\internal\jpphone\FreeDial',
            self::FLAG_FREE_ACCESS  => 'jp3cki\yii2\validators\internal\jpphone\FreeAccess',
            self::FLAG_NAV_DIAL     => 'jp3cki\yii2\validators\internal\jpphone\NavDial',
            self::FLAG_DIAL_Q2      => 'jp3cki\yii2\validators\internal\jpphone\Q2',
            self::FLAG_PAGER        => 'jp3cki\yii2\validators\internal\jpphone\Pager',
            // 固定電話はコストが高いので最後に検査する
            self::FLAG_LANDLINE     => 'jp3cki\yii2\validators\internal\jpphone\Landline',
        ];
        foreach ($classMap as $classFlag => $className) {
            if (($this->types & $classFlag) === $classFlag) {
                $impl = new $className();
                $impl->hyphen = $this->hyphen;
                if ($impl->validate($number)) {
                    return true;
                }
            }
        }
        return false;
    }
}
