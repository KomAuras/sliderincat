<?php
return array(
    'name' => 'Слайдер категории',
    'img' => 'img/sliderincat.png',
    'description' => 'Слайдер на странице категории',
    'vendor' => 1010465,
    'version' => '1.1',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'backend_category_dialog' => 'backendCategoryDialog',
        'category_save' => 'saveCategorySettings',
        'category_delete' => 'deleteCategory'
    ),
);