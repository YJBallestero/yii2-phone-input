<?php

namespace yjballestero\extensions\phoneInput;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Behavior of the phone input widget. Auto-formats the phone value for the JS-widget.
 *
 * @package yjballestero\extensions\phoneInput
 *
 * @property-read \libphonenumber\PhoneNumberUtil $phoneUtil
 */
class PhoneInputBehavior extends AttributeBehavior
{
    /**
     * @var int
     */
    public int $saveformat = PhoneNumberFormat::E164;
    /**
     * @var int
     */
    public int $displayFormat = PhoneNumberFormat::INTERNATIONAL;
    /**
     * @var string
     */
    public string $phoneAttribute = 'phone';
    /**
     * @var string
     */
    public string $default_region;

    /**
     * @var string
     */
    public ?string $countryCodeAttribute = null;

    public function init(): void {
        parent::init();
        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->phoneAttribute,
                BaseActiveRecord::EVENT_AFTER_FIND      => $this->phoneAttribute,
            ];
        }
    }

    /**
     * @return array
     */
    public function events(): array {
        $events = parent::events();
        $events[BaseActiveRecord::EVENT_AFTER_FIND] = 'formatAttributes';

        return $events;
    }

    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     *
     * @param Event $event
     */
    public function evaluateAttributes($event): void {
        if (!empty($this->attributes[$event->name])) {
            $attributes = (array)$this->attributes[$event->name];
            foreach ($attributes as $attribute) {
                if (is_string($attribute) && $this->owner->$attribute) {
                    try {
                        /** @var \libphonenumber\PhoneNumber $phoneValue */
                        $phoneValue = $this->getPhoneUtil()->parse($this->owner->$attribute, $this->default_region);
                        $this->owner->$attribute = $this->getPhoneUtil()->format($phoneValue, $this->saveformat);
                        if ($this->countryCodeAttribute !== null) {
                            $this->owner->{$this->countryCodeAttribute} = $phoneValue->getCountryCode();
                        }
                    } catch (NumberParseException $e) {
                    }
                }
            }
        }
    }

    /**
     * @param $event
     *
     * @return void
     */
    public function formatAttributes($event): void {
        if (!empty($this->attributes[$event->name])) {
            $attributes = (array)$this->attributes[$event->name];
            foreach ($attributes as $attribute) {
                if (is_string($attribute) && $this->owner->$attribute) {
                    try {
                        /** @var \libphonenumber\PhoneNumber $phoneValue */
                        $phoneValue = $this->getPhoneUtil()->parse($this->owner->$attribute, $this->default_region);
                        $this->owner->$attribute = $this->getPhoneUtil()->format($phoneValue, $this->displayFormat);
                    } catch (NumberParseException $e) {
                    }
                }
            }
        }
    }

    /**
     * @return \libphonenumber\PhoneNumberUtil
     */
    protected function getPhoneUtil(): PhoneNumberUtil {
        return PhoneNumberUtil::getInstance();
    }
}
