<?php

namespace yjballestero\phoneInput;

use Yii;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberType;
use yii\validators\Validator;
use yii\helpers\Json;

/**
 * Validates the given attribute value with the PhoneNumberUtil library.
 * @package yjballestero\phoneInput
 */
class PhoneInputValidator extends Validator
{
    /**
     * @var mixed
     */
    public mixed $region;
    /**
     * @var integer|null
     */
    public ?int $type;

    /**
     * @var string
     */
    public string $default_region;

    /**
     * @return void
     */
    public function init(): void {
        if (!$this->message) {
            $this->message = Yii::t('yii', 'The format of {attribute} is invalid.');
        }
        parent::init();
    }

    /**
     * @param mixed $value
     * @return array|null
     */
    protected function validateValue($value): ?array {
        $valid = false;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            /** @var \libphonenumber\PhoneNumber $phoneProto */
            $phoneProto = $phoneUtil->parse($value, $this->default_region);

            if ($this->region !== null) {
                $regions = is_array($this->region) ? $this->region : [$this->region];
                foreach ($regions as $region) {
                    if ($phoneUtil->isValidNumberForRegion($phoneProto, $region)) {
                        $valid = true;
                        break;
                    }
                }
            } else if ($phoneUtil->isValidNumber($phoneProto)) {
                $valid = true;
            }

            if (($this->type !== null) && PhoneNumberType::UNKNOWN !== $type = $phoneUtil->getNumberType($phoneProto)) {
                $valid = $valid && $type === $this->type;
            }

        } catch (NumberParseException $e) {
        }
        return $valid ? null : [$this->message, []];
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view): ?string {

        $options = Json::htmlEncode([
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute)
            ], Yii::$app->language)
        ]);

        return <<<JS
let telInput = $(attribute.input),
    options = $options ;

if ($.trim(telInput.val())) {
    if (!telInput.intlTelInput("isValidNumber")) {
        messages.push(options.message);
    }
}
JS;
    }
}
