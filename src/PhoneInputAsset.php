<?php

namespace yjballestero\extensions\phoneInput;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Asset Bundle of the phone input widget. Registers required CSS and JS files.
 * @package yjballestero\extensions\phoneInput
 */
class PhoneInputAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@bower/intl-tel-input';
    /** @var array */
    public $css = ['build/css/intlTelInput.css'];
    /** @var array */
    public $js = [
        'build/js/utils.js',
        'build/js/intlTelInput.js',
    ];
    /** @var array */
    public $depends = [JqueryAsset::class];
}
