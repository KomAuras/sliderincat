<?php

/**
 * Сохранение размеров эскизов изображений определённого типа для категорий
 */
class shopSliderincatPluginSaveimagesizeController extends waJsonController
{
    function execute()
    {
        $size = waRequest::post('str', waRequest::TYPE_STRING);

        $app_settings = new waAppSettingsModel();
        $app_settings->set(wa()->getApp('shop') . '.sliderincat', 'image', $size);

        $data = explode('X', $size);
        $data['width'] = array_shift($data);
        $data['height'] = array_shift($data);

        $this->response['size'] = $data;
    }
}