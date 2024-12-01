<?php
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
if(!defined('db_file')) define('db_file',__DIR__.'./db/electric_billing_db.db');
function my_udf_md5($string) {
    return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `admin_list` (
            `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `type` INTEGER NOT NULL Default 1,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        //User Comment
        // Type = [ 1 = Administrator, 2 = Cashier]
        // Status = [ 1 = Active, 2 = Inactive]

        $this->exec("CREATE TABLE IF NOT EXISTS `settings_list` (
            `meta_field` TEXT NOT NULL,
            `meta_value` TEXT NOT NULL
        ) ");
        $this->exec("CREATE TABLE IF NOT EXISTS `client_list` (
            `client_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `firstname` TEXT NOT NULL,
            `lastname` TEXT NOT NULL,
            `middlename` TEXT NULL,
            `gender` TEXT NOT NULL,
            `contact` TEXT NOT NULL,
            `email` TEXT NOT NULL,
            `address` TEXT NOT NULL,
            `dob` DATE NOT NULL,
            `status` TEXT NOT NULL DEFAULT 1,
            `date_created` TIMESTAMP NOT NULL Default CURRENT_TIMESTAMP
        ) ");
        $this->exec("CREATE TABLE IF NOT EXISTS `connection_list` (
            `connection_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `client_id` INTEGER NOT NULL,
            `code` TEXT NOT NULL,
            `meter_default` TEXT NOT NULL,
            `type` TEXT NULL,
            `status` TEXT NOT NULL DEFAULT 1,
            `date_created` TIMESTAMP NOT NULL Default CURRENT_TIMESTAMP,
            FOREIGN KEY(`client_id`) REFERENCES `client_list`(`client_id`) ON DELETE CASCADE
        ) ");
        $this->exec("CREATE TABLE IF NOT EXISTS `bill_list` (
            `bill_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `connection_id` INTEGER NOT NULL,
            `month_from` DATE NOT NULL,
            `month_to` DATE NOT NULL,
            `charge_per_unit` REAL NOT NULL,
            `previous_reading` TEXT NOT NULL,
            `current_reading` TEXT NOT NULL,
            `consumed` TEXT NOT NULL,
            `amount` REAL NOT NULL,
            `due` DATE NOT NULL,
            `status` TEXT NOT NULL DEFAULT 0,
            `date_created` TIMESTAMP NOT NULL Default CURRENT_TIMESTAMP,
            FOREIGN KEY(`connection_id`) REFERENCES `connection_list`(`connection_id`) ON DELETE CASCADE
        ) ");

        
        // $this->exec("CREATE TRIGGER IF NOT EXISTS updatedTime_prod AFTER UPDATE on `vacancy_list`
        // BEGIN
        //     UPDATE `vacancy_list` SET date_updated = CURRENT_TIMESTAMP where vacancy_id = vacancy_id;
        // END
        // ");

        $this->exec("INSERT or IGNORE INTO `admin_list` VALUES (1,'Administrator','admin',md5('admin123'),1,1, CURRENT_TIMESTAMP)");
        $this->exec("INSERT or IGNORE INTO `settings_list` VALUES 
            ('company_name','Electric Provider Corp.'),
            ('office_address','Sample Electric Provider Address'),
            ('contact','09123456789/456-9877-1232'),
            ('company_email','info@electricprovider.com'),
            ('charge_per_unit','12')
        ");

    }
    function settings(){
        $sql = "SELECT * FROM settings_list";
        $qry = $this->query($sql);
        while($row = $qry->fetchArray()){
            $_SESSION['settings'][$row['meta_field']] = $row['meta_value'];
        }
        return json_encode($_SESSION['settings']);
    }
    function update_settings(){
        $data = "";
        foreach($_POST as $k => $v){
            if(!empty($data)) $data .= ", ";
            $data .= "('{$k}','{$v}')";
        }
        if(!empty($data)){
            $this->query("DELETE FROM settings_list");
        }
        $sql = "INSERT INTO settings_list (`meta_field`,`meta_value`) VALUES {$data}";
        $save = $this->query($sql);
        if($save){
            $this->settings();
            return true;
        }else{
            return false;
        }
    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();
if(!isset($_SESSION['settings'])){
    $conn->settings();
}