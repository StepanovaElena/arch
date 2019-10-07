<?php

//Файл с функциями нашего движка


//Функция, возвращает текст шаблона $page с подстановкой переменных
//из массива $params, содержимое шабона $page подставляется в
//переменную $content главного шаблона layout для всех страниц

function render($page, $params = []) {

    $menu = [
        'Home'=>'?page=index',
        'Catalog' => [
            'Gallery' => '?page=catalog',
            'Subitem 2' => '?page=index',
            'Subitem 3' => '?page=index'
        ],
        'About' => [
            'Subitem 1' => '?page=index',
            'Subitem 2' => '?page=index'
        ],
        'Blog'=>'?page=index',
        'Contacts'=>'?page=index',
    ];


    return renderTamplate(LAYOUTS_DIR . 'layout', [
        "content" => renderTamplate($page, $params),
        "menu" => renderMenu($menu, 'class = "topmenu"')
    ]);
}


//Функция возвращает текст шаблона $page с подставленными переменными из
//массива $params, просто текст
function renderTamplate($page, $params = [])
{
    ob_start();

    if (!is_null($params)) {
        extract($params);
    }


    $fileName = TEMLATES_DIR . $page . '.php';

    if (file_exists($fileName)) {
        include $fileName;
    } else {
        Die("Страницы не существует, 404");
    }

    return ob_get_clean();
}


//Функция построения меню

function renderMenu($menu, $class) {
    $out = "<ul {$class}>";
    foreach ($menu as $name => $href) {
        if (is_array($href)) {
            $class = 'class = "submenu"';
            $out .= "<li><a href='#'>" . $name . "</a>" . renderMenu($href, $class)."</li>";
        } else {
            $out .= "<li><a href=\"{$href}\">{$name}</a></li>";
        }
    }
    $out .= '</ul>';
    return $out;
}

//Функция построения галереи

function renderGallery () {

    $links = [];

    $pathSmall = "./img/small";
    $smallImg = searchImage($pathSmall);

    $pathBig = "./img/big";
    $bigImg = searchImage($pathBig);

    foreach ($smallImg as $val) {
        if (in_array($val, $bigImg)) {
            $links[] = $val;
        } else {
            $links[] = "#";
        }
    }

    return $links;
}

function searchImage($path) {

    $links = [];

    if($handle = opendir($path)){
        while($entry = readdir($handle)){
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            array_push($links, $entry );
        }
        closedir($handle);
    }

    return $links;
}

//Изменение размера файлов

function create_thumbnail($path, $save, $width, $height){

    $info = getimagesize($path); //получаем размеры картинки и ее тип

    $size = [$info[0], $info[1]]; //закидываем размеры в массив

    //В зависимости от расширения картинки вызываем соответствующую функцию

    if ($info['mime'] == 'image/png') {
        $src = imagecreatefrompng($path);
    } elseif ($info['mime'] == 'image/jpeg') {
        $src = imagecreatefromjpeg($path);
    } elseif ($info['mime'] == 'image/gif') {
        $src = imagecreatefromgif($path);
    } else {
        return false;
    }

    $thumb = imagecreatetruecolor($width, $height); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

    $src_aspect = $size[0] / $size[1]; //отношение ширины к высоте исходника

    $thumb_aspect = $width / $height; //отношение ширины к высоте аватарки

    if ($src_aspect < $thumb_aspect) {

        //узкий вариант (фиксированная ширина)
        //$scale = $width / $size[0];
        //$new_size = array($width, $width / $src_aspect);
        //$src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2);
        //Ищем расстояние по высоте от края картинки до начала картины после обрезки
        //} else if ($src_aspect > $thumb_aspect) {


        //широкий вариант (фиксированная высота)
        $scale = $height / $size[1];
        $new_size = [$height * $src_aspect, $height];
        $src_pos = [($size[0] * $scale - $width) / $scale / 2, 0]; //Ищем расстояние по ширине от края картинки до начала картины после обрезки

    } else {
        //другое
        $new_size = [$width, $height];
        $src_pos = [0, 0];
    }

    $new_size[0] = max($new_size[0], 1);
    $new_size[1] = max($new_size[1], 1);

    //Копирование и изменение размера изображения с ресемплированием
    imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);

    return imagejpeg($thumb, $save);
}

//Функция загрузки изображений

function imageLoading () {

    if (!empty($_FILES['userfile'])) {

        $file = $_FILES['userfile'];

        $srcFileName = $file['name'];

        $sizeFile = $file['size'];

        $filePath = $file['tmp_name'];

        $newFilePath = './uploads/' . $srcFileName;

        //Ограничение по загружаемым форматам
        $allowedExtensions = ['jpg', 'png', 'gif'];
        //Ограничение по объему файла
        $maxFile = 1024 * 1024 * 8;
        //Получение расширения файла
        $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

        //Проверка на формат файла
        if (!in_array($extension, $allowedExtensions)) {
            return $error = 1;

            //Проверка файла на размер
        } elseif ($sizeFile > $maxFile || ($file['error'] == UPLOAD_ERR_INI_SIZE)) {
            return $error = 2;

            //Проверка успешной загрузке на сервер временного файла
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            return $error = 3;

            //Проверка на существоввние файла с таким именем
        } elseif (file_exists($newFilePath)) {
            return $error = 4;

            //Перемещение файла из врменной паки в указанную
        } elseif (!move_uploaded_file($filePath, $newFilePath)) {
            //Ошибка при перемещении файла из временной папки
            return $error = 5;

        } else {
            //Загрузка файла из временной папки в конечную
            move_uploaded_file($filePath, $newFilePath);
            //Выводим сообщение об удачной загрузке файла
            //Создаем уменьшенную копию картинки
            create_thumbnail($newFilePath, './img/small/' . $srcFileName, '150', '100');
            //Перемещаем полученный файл в папку с оригиналами
            copy($newFilePath, './img/big/' . $srcFileName); // делаем копию
            unlink($newFilePath); // удаляем оригинал
        }
        //Выводим сообщение об удачной загрузке файла
        return $error = 0;
    }
}