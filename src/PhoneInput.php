<?php

namespace yjballestero\extensions\phoneInput;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Widget of the phone input
 *
 * @package yjballestero\extensions\phoneInput
 */
class PhoneInput extends InputWidget
{
    /** @var string HTML tag type of the widget input ("tel" by default) */
    public string $htmlTagType = 'tel';
    /** @var array Default widget options of the HTML tag */
    public array $defaultOptions = ['autocomplete' => "off", 'class' => 'form-control'];
    /**
     * @link https://github.com/jackocnr/intl-tel-input#options More information about JS-widget options.
     * @var array Options of the JS-widget
     */
    public array $jsOptions = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function init(): void {
        parent::init();
        PhoneInputAsset::register($this->view);
        $id = ArrayHelper::getValue($this->options, 'id');
        $jsOptions = $this->jsOptions ? Json::encode($this->jsOptions) : "";
        $jsInit = <<<JS
(function ($) {
    "use strict";
    $('#$id').intlTelInput($jsOptions);
})(jQuery);
JS;
        $this->view->registerJs($jsInit);
        if ($this->hasModel()) {
            $js = <<<JS
(function ($) {
    "use strict";
    $('#$id').parents('form').on('submit', function() {
        $('#$id').val($('#$id').intlTelInput('getNumber'));
    });
})(jQuery);
JS;
            $this->view->registerJs($js);
        }
    }

    /**
     * @return string
     */
    public function run(): string {
        $options = ArrayHelper::merge($this->defaultOptions, $this->options);
        if ($this->hasModel()) {
            return Html::activeInput($this->htmlTagType, $this->model, $this->attribute, $options);
        }

        return Html::input($this->htmlTagType, $this->name, $this->value, $options);
    }
}
