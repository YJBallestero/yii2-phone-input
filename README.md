Yii2 International telephone numbers - Asset Bundle, Behavior, Validator, Widget
================================================================================

[![Latest Stable Version](https://poser.pugx.org/yjballestero/yii2-phone-input/v/stable.svg)](https://packagist.org/packages/yjballestero/yii2-phone-input)
[![Total Downloads](https://poser.pugx.org/yjballestero/yii2-phone-input/downloads.svg)](https://packagist.org/packages/yjballestero/yii2-phone-input)
[![Latest Unstable Version](https://poser.pugx.org/yjballestero/yii2-phone-input/v/unstable.svg)](https://packagist.org/packages/yjballestero/yii2-phone-input)
[![License](https://poser.pugx.org/yjballestero/yii2-phone-input/license.svg)](https://packagist.org/packages/yjballestero/yii2-phone-input)
[![Build Status](https://travis-ci.org/yjballestero/yii2-phone-input.svg?branch=master)](https://travis-ci.org/yjballestero/yii2-phone-input)

## Requirements

This extension uses:

* PHP 8.1+.
* Yii2 2.0.45+

- [A jQuery plugin for entering and validating international telephone numbers](https://github.com/Bluefieldscom/intl-tel-input)
- [PHP version of Google's phone number handling library](https://github.com/giggsey/libphonenumber-for-php)

Original demo can be found here - [http://jackocnr.com/intl-tel-input.html](http://jackocnr.com/intl-tel-input.html).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ php composer.phar require "yjballestero/yii2-phone-input" "*"
```

or add

```
"yjballestero/yii2-phone-input": "*"
```

to the `require` section of your `composer.json` file.

## Usage

![Phone input](screenshot.png "Phone input")

Using as an `ActiveField` widget with the preferred countries on the top:

```php
use yjballestero\extensions\phoneInput\PhoneInput;

echo $form->field($model, 'phone_number')->widget(PhoneInput::className(), [
    'jsOptions' => [
        'preferredCountries' => ['no', 'pl', 'ua'],
    ]
]);
```

Using as a simple widget with the limited countries list:

```php
use yjballestero\extensions\phoneInput\PhoneInput;

echo PhoneInput::widget([
    'name' => 'phone_number',
    'jsOptions' => [
        'allowExtensions' => true,
        'onlyCountries' => ['no', 'pl', 'ua'],
    ]
]);
```

Using phone validator in a model (validates the correct country code and phone format):

```php
namespace frontend\models;

use yjballestero\extensions\phoneInput\PhoneInputValidator;

class Company extends Model
{
    public $phone;

    public function rules()
    {
        return [
            [['phone'], 'string'],
            [['phone'], PhoneInputValidator::className()],
        ];
    }
}
```

or if you need to validate phones of some countries:

```php
namespace frontend\models;

use yjballestero\extensions\phoneInput\PhoneInputValidator;

class Company extends Model
{
    public $phone;

    public function rules()
    {
        return [
            [['phone'], 'string'],
            // [['phone'], PhoneInputValidator::className(), 'region' => 'UA'],
            [['phone'], PhoneInputValidator::className(), 'region' => ['PL', 'UA']],
        ];
    }
}
```

Using phone behavior in a model (auto-formats phone string to the required phone format):

```php
namespace frontend\models;

use yjballestero\extensions\phoneInput\PhoneInputBehavior;

class Company extends Model
{
    public $phone;

    public function behaviors()
    {
        return [
            'phoneInput' => PhoneInputBehavior::className(),
        ];
    }
}
```

You can also thanks to this behavior save to database country code of the phone number. Just add your attribute as
`countryCodeAttribute` and it'll be inserted into database with the phone number.

```php
namespace frontend\models;

use yjballestero\extensions\phoneInput\PhoneInputBehavior;

class Company extends Model
{
    public $phone;
    public $countryCode;

    public function behaviors()
    {
        return [
            [
                'class' => PhoneInputBehavior::className(),
                'countryCodeAttribute' => 'countryCode',
            ],
        ];
    }
}
```

> Note: `nationalMode` option is very important! In case if you want to manage phone numbers with country/operator code

- you have to set `nationalMode: false` in widget options
  (for example, `PhoneInput::widget(...options, ['jsOptions' => ['nationalMode' => false]])`).
