<?php

//Удаление данных из общедоступной области
$path = wa()->getDataPath("sliderincatPlugin/", true, 'shop');
waFiles::delete($path, false);

//Удаление данных из защищённой области
$path = wa()->getDataPath("sliderincatPlugin/", false, 'shop');
waFiles::delete($path, false);