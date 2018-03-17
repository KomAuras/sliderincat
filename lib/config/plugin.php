<?php
return array(
    'name' => 'Слайдер на странице категории',
    'img' => 'img/sliderincat.png',
    'description' => '',
    'vendor' => 1010465,
    'version' => '1.0',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'backend_category_dialog' => 'backendCategoryDialog',
        'category_save' => 'saveCategorySettings',
        'category_delete' => 'deleteCategory'
    ),
);