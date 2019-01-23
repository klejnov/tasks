<?php
/**
 * Connection MySQL
 */

class DataBase
{
    private $link;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * @return $this
     */
    private function connect()
    {
        $config = require_once './config.php';
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];
        $this->link = new PDO($dsn, $config['username'], $config['password']);
        $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $this;
    }

    /**
     * @param $sql
     *
     * @return mixed
     */
    public function execute($sql)
    {
        $sth = $this->link->prepare($sql);
        return $sth->execute();
    }

    /**
     * @return mixed
     */
    public function lastInsertId()
    {
        return $this->link->lastInsertId();
    }

    /**
     * @param $sql
     *
     * @return array
     */
    public function query($sql)
    {
        $sth = $this->link->prepare($sql);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }

    /**
     * @param $name
     * @param $email
     * @param $phone
     * @param $text
     * @param $ip
     */
    function saveMessage($name, $email, $phone, $text, $ip)
    {

        $sth = $this->link->prepare(
            "INSERT INTO 
              messages (name, email, phone, text, ip) 
              VALUES (
              :sql_name,
              :sql_email,
              :sql_phone,
              :sql_text,
              :sql_ip
              );"
        );
        $sth->execute(array(
            'sql_name'  => $name,
            'sql_email' => $email,
            'sql_phone' => $phone,
            'sql_text'  => $text,
            'sql_ip'    => $ip
        ));
    }

    /**
     * @param $dbName
     *
     * Backing up the database in the directory /../../backup/
     */
    static function backup()
    {
        $config = include './config.php';
        $today = date("Y.m.d_H-i-s");
        $dir = __DIR__;
        shell_exec("mysqldump -u{$config['username']} -p{$config['password']} {$config['db_name']} > $dir/../../backup/dump_$today.sql");
    }

}
