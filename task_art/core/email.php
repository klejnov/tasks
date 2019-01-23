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

    function formValidation($name, $email)
    {

        if (strlen(trim($name)) == 0) {

            $result = [
                'send'      => false,
                'error-msg' => "Вы ввели пустое имя"
            ];
            $result = json_encode($result);
            echo $result;

            exit();
        }

        $valid_email = preg_match("/^([\p{L}\.\-\d]+)@([\p{L}\-\.\d]+)((\.(\p{L}){2,63})+)$/u", $email);

        if ($valid_email == 0) {

            $result = [
                'send'      => false,
                'error-msg' => "Неверный адрес почты"
            ];
            $result = json_encode($result);
            echo $result;

            exit();
        }
    }

    if (isset($_POST["AjaxAction"]) && $_POST['AjaxAction'] == 'Message') {

        $name = $_POST["AjaxName"];
        $email = trim(mb_convert_case($_POST["AjaxEmail"], MB_CASE_LOWER, "UTF-8"));
        $phone = $_POST["AjaxPhone"];
        $text = $_POST["AjaxText"];
        $ip = $_SERVER['REMOTE_ADDR'];

        formValidation($name, $email);

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
                'send'          => false,
                'error-msg'     => "Увы, произошла ошибка. Попробуйте отправить сообщение позже",
                'error-msg-sql' => $e->getMessage()
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
