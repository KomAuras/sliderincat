<?php
/**
 * Удаление изображения для категории
*/
class shopSliderincatPluginDeleteimageController extends waJsonController
{
    function execute()
    {
        $cat_id = waRequest::post('cat_id', waRequest::TYPE_INT);
        $type = waRequest::post('type', waRequest::TYPE_STRING);
        $id = waRequest::post('id', waRequest::TYPE_INT);
        $ext = waRequest::post('ext', waRequest::TYPE_STRING);

        $model = new shopSliderincatModel();
        $path = wa()->getDataPath("sliderincatPlugin/categories/{$cat_id}/", true, 'shop');
        $original_path = wa()->getDataPath("sliderincatPlugin/categories/{$cat_id}/", false, 'shop');
        $model->deleteById($id);
        waFiles::delete($path.$type.'_'.$id.'.'.$ext);
        waFiles::delete($original_path.$type.'_'.$id.'.'.$ext);

        $this->response['state'] = true;
    }
}