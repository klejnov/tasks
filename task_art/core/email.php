<?php

define('DS', DIRECTORY_SEPARATOR);

/**
 * Автоматическая загрузка классов
 */

spl_autoload_register(function ($class_name) {

    $patchFile = 'classes' . DS . $class_name . '.php';

    if (!file_exists($patchFile)) {
        echo "Файла с классом $class_name нет";
        die();
    }
    include $patchFile;
});

try {

    if (isset($_POST["AjaxAction"]) && $_POST['AjaxAction'] == 'Message') {

        $name = $_POST["AjaxName"];
        $email = mb_convert_case($_POST["AjaxEmail"], MB_CASE_LOWER, "UTF-8");
        $phone = $_POST["AjaxPhone"];
        $text = $_POST["AjaxText"];
        $ip = $_SERVER['REMOTE_ADDR'];

        try {
            $db = new DataBase();
            $db->saveMessage($name, $email, $phone, $text, $ip);

            $result = [
                'send' => true
            ];
            $result = json_encode($result);
            echo $result;
        } catch (Throwable $e) {
            $result = [
                'send'      => false,
                'error-msg' => $e->getMessage()
            ];
            $result = json_encode($result);
            echo $result;
        }

        exit();
    }
} catch (Throwable $e) {

    print <<<HTML_BLOCK
Выброшено исключение:   <b>{$e->getMessage()}</b><br>
Строка:                 <b>{$e->getLine()}</b><br>
В файле:                <b>{$e->getFile()}</b><br>
HTML_BLOCK;
}



/**
 * Вызов метода резервного копирования текущей базы данных
 */

//DataBase::backup();
