<?php

namespace yjballestero\phoneInput;

use Yii;
use Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Widget of the phone input
 *
 * @package yjballestero\phoneInput
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
        Yii::setAlias('extIntlTelInput', "@vendor/jackocnr/intl-tel-input");
        $asset = PhoneInputAsset::register($this->view);
        $id = ArrayHelper::getValue($this->options, 'id');

        if ($_utilUrl = Yii::$app->assetManager->getActualAssetUrl($asset, $asset->js[0])) {
            $this->jsOptions['utilsScript'] = $_utilUrl;
        }

        $jsOptions = $this->jsOptions ? Json::encode($this->jsOptions) : "";
        try {
            $hasModel = json_encode($this->hasModel(), JSON_THROW_ON_ERROR);

        } catch (Exception $exception) {
            $hasModel = false;
        }

        $jsInit = <<<JS
(function ($) {
    "use strict";
    let input = document.querySelector("#$id"),
        iti = intlTelInput(input, $jsOptions);
    if ($hasModel){
        // console.info(iti.getNumber())
        $("#$id").parents('form').on('submit',function(){
            $("#$id").val(iti.getNumber());
        });
    }
})(jQuery);
JS;
        $this->view->registerJs($jsInit);

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
