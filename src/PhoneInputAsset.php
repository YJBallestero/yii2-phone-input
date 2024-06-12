<?php

namespace yjballestero\phoneInput;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Asset Bundle of the phone input widget. Registers required CSS and JS files.
 *
 * @package yjballestero\phoneInput
 */
class PhoneInputAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@extIntlTelInput';
    /** @var array */
    public $css = ['build/css/intlTelInput.css'];
    /** @var array */
    public $js = [
        'build/js/utils.js',
        'build/js/intlTelInputWithUtils.min.js',
    ];
    /** @var array */
    public $depends = [JqueryAsset::class];
}
