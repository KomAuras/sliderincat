<?php

class shopSliderincatPlugin extends shopPlugin
{
    /**
     * Метод для хука "backend_category_dialog".
     * Выводит список для манипуляции с изображениями в окне настроек категории.
     *
     * @param array $category - данные о категории из базы данных
     * @return string - содержимое файла CategoryFields.html
     */
    public function backendCategoryDialog($category)
    {
        $view = wa()->getView();

        $plugin = wa()->getPlugin('sliderincat');
        $size = $this->convert_to_string($plugin->getSettings('image'));

        $model = new shopSliderincatModel();
        $images = $model->getAllofCat($category['id']);

        $view->assign('images', $images);
        $view->assign('sizes', $size);
        $view->assign('cat_id', $category['id']);
        $path = wa()->getAppPath('plugins/sliderincat/templates/', 'shop');
        $content = $view->fetch($path . 'CategoryFields.html');
        return $content;
    }


    /**
     * Метод для хука "category_save".
     *
     * @param array $category - данные о категории из базы данных
     */
    public function saveCategorySettings($category)
    {
        /*@var waRequestFileIterator*/
        $images = waRequest::file('sliderincat_image_file');

        foreach ($images as $image) {
            if (($image->uploaded())) {
                $this->SaveImageData($image, $category);
            }
        }
    }


    public function deleteCategory($category)
    {
        $target_model = new shopSliderincatModel();

        $public_path = wa()->getDataPath("sliderincatPlugin/categories/{$category['id']}/", true, 'shop');
        $protected_path = wa()->getDataPath("sliderincatPlugin/categories/{$category['id']}/", false, 'shop');

        $target_model->deleteCategoryDataById($category['id']);

        waFiles::delete($public_path, false);
        waFiles::delete($protected_path, false);
    }

    /**
     * Метод выводит ссылку на изображение определённого типа для категории
     *
     * @param int $id - идентификатор категории
     *
     * @return array | false
     */
    public static function getCategoryImage($id)
    {
        $model = new shopSliderincatModel();
        $path = wa()->getDataUrl("sliderincatPlugin/categories/{$id}/", true, 'shop');
        $images = $model->getByField('category_id', $id, true);
        if (count($images)) {
            $data = array();
            foreach ($images as $image) {
                $data[] = $path . 'image_' . $image['id'] . '.' . $image['ext'];
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Преобразование массива данных в строку
     *
     * @param mixed $data - массив со значениями ширины и высоты изображения
     * @return string - "width X height"
     */
    protected function convert_to_string($data)
    {
        $result = '';
        if (!is_array($data)) {
            $result = $data;
        } else if (is_array($data)) {
            $result = implode(' X ', $data);
        }

        return $result;
    }

    /**
     * Сохранение изображения
     *
     * @param waRequestFileIterator $file - файл выбранного изображения;
     * @param array $category - данные о категории из базы данных;
     */
    protected function SaveImageData($file, $category)
    {
        $model = new shopSliderincatModel();
        $plugin = wa()->getPlugin('sliderincat');

        $data = array(
            'category_id' => $category['id'],
            'upload_datetime' => date('Y-m-d H:i:s'),
            'file_name' => basename($file->name),
            'size' => $file->size,
            'ext' => $file->extension
        );

        try {
            $image = $file->waImage();
            $path = wa()->getDataPath("sliderincatPlugin/categories/{$category['id']}/", true, 'shop');
            $original_path = wa()->getDataPath("sliderincatPlugin/categories/{$category['id']}/", false, 'shop');
            $data['width'] = $image->width;
            $data['height'] = $image->height;

            $res = $model->insert($data);
            if ($res) {
                $resize = $plugin->getSettings('image');
                $image->save($original_path . 'image_' . $res . '.' . $data['ext']);
                $image = shopCreatethumbnails::generateThumb($original_path . 'image_' . $res . '.' . $data['ext'], $resize);
                $image->save($path . 'image_' . $res . '.' . $data['ext']);
            }
        } catch (waException $e) {
            echo "%sliderincat_plugin%Файл {$data['file_name']} не является изображением, либо произошла другая ошибка -  " . $e->getMessage() . "%sliderincat_plugin%";
        }
    }
}