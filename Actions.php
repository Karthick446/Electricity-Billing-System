<?php 
session_start();
require_once('DBConnection.php');

Class Actions extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function login(){
        extract($_POST);
        $sql = "SELECT * FROM admin_list where username = '{$username}' and `password` = '".md5($password)."' ";
        @$qry = $this->query($sql)->fetchArray();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }else{
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach($qry as $k => $v){
                if(!is_numeric($k))
                $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function logout(){
        session_destroy();
        header("location:./admin");
    }
    function save_admin(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(empty($id)){
            $cols[] = 'password';
            $values[] = "'".md5($username)."'";
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

       
        @$check= $this->query("SELECT count(admin_id) as `count` FROM admin_list where `username` = '{$username}' ".($id > 0 ? " and admin_id != '{$id}' " : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `admin_list` {$data}";
            }else{
                $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$id}'";
            }
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                if(empty($id))
                $resp['msg'] = 'New User successfully saved.';
                else
                $resp['msg'] = 'User Details successfully updated.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Saving User Details Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function delete_admin(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `admin_list` where rowid = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'User successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function save_settings(){
        extract($_POST);
        file_put_contents('./about.html',htmlentities($about));
        file_put_contents('./welcome.html',htmlentities($welcome));
        $update = $this->update_settings();
        if($update){
            $resp['status'] = "success";
            $resp['msg'] = "Settings successfully updated.";
        }else{
            $resp['status'] = "failed";
            $resp['msg'] = "Failed to update settings. Error: ".$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_client(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->escapeString($v);
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `client_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `client_list` set {$data} where client_id = '{$id}'";
        }
      
        
        @$save = $this->query($sql);
        if($save){
            $resp['status']="success";
            if(empty($id))
                $resp['msg'] = "Client successfully saved.";
            else
                $resp['msg'] = "Client successfully updated.";
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Client Failed.";
            else
                $resp['msg'] = "Updating Client Failed.";
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function delete_client(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `client_list` where client_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Client successfully deleted.';
            if(is_file(__DIR__.'uploads/'.$id.'.png'))
            unlink(__DIR__.'uploads/'.$id.'.png');
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_connection(){
        extract($_POST);
        $data = "";
        if(empty($id)){
            $code = '';
            while(true){
                $code = mt_rand(1,999999999999);
                $code = sprintf("%'.012d",$code);
                $chk = $this->query("SELECT count(connection_id) as `count` FROM connection_list where code = '{$code}'")->fetchArray()['count'];
                if($chk <= 0){
                    break;
                }
            }
            $_POST['code'] = $code;
        }
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

        
        
        if(empty($id)){
            $sql = "INSERT INTO `connection_list` {$data}";
        }else{
            $sql = "UPDATE `connection_list` set {$data} where connection_id = '{$id}'";
        }
        @$save = $this->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
            $resp['msg'] = 'Connection Successfully created.';
            else
            $resp['msg'] = 'Connection Successfully updated.';
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'Failed to create the Connection. Error: '.$this->lastErrorMsg();
            $resp['sql'] =$sql;
        }
        return json_encode($resp);
    }
    function delete_connection(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `connection_list` where connection_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['connection_flashdata']['type'] = 'success';
            $_SESSION['connection_flashdata']['msg'] = 'Connection successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function get_previous(){
        extract($_POST);
        $where = "";
        if($bill_id > 0){
            $where = " and bill_id != '{$bill_id}' ";
        }
        $prevoius = $this->query("SELECT current_reading from bill_list where connection_id = '{$connection_id}' {$where} order by date_created desc limit 1")->fetchArray();
        if(!$prevoius){
            $prevoius = $this->query("SELECT meter_default from connection_list where connection_id = '{$connection_id}' ")->fetchArray()['meter_default'];
        }else{
            $prevoius = $prevoius['current_reading'];
        }
        $resp['status'] = 'success';
        $resp['reading'] = $prevoius;
        return json_encode($resp);
        
    }
    function save_bill(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = $this->escapeString(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        $check = $this->query("SELECT count(`bill_id`) as `count` FROM bill_list where month_from = '{$month_from}' and month_to = '{$month_to}' and connection_id = '{$connection_id}' ".($id > 0 ? " and bill_id != '{$id}'" : ""))->fetchArray()['count'];
        if($check > 0){
            $resp['status']="failed";
            $resp['sql']="SELECT count(`bill_id`) as `count` FROM bill_list where month_from = '{$month_from}' and month_to = '{$month_to}' and connection_id = '{$connection_id}' ".($id > 0 ? " and bill_id != '{$id}'" : "");
            $resp['msg'] = "Selected Client's Connection has already billed for the selected Reading Range.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `bill_list` ({$cols_join}) VALUES ($vals_join)";
            }else{
                $sql = "UPDATE `bill_list` set {$data} where bill_id = '{$id}'";
            }
        
            @$save = $this->query($sql);
            if($save){
                $resp['status']="success";
                $_SESSION['bill_flashdata']['type']="success";
                if(empty($id)){
                    $resp['msg'] = " Successfull....";
                }else{
                    $resp['msg'] = " Successfull....";
                }
                $_SESSION['bill_flashdata']['msg']=$resp['msg'];
            }else{
                $resp['status']="failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Bill Failed.";
                else
                    $resp['msg'] = "Updating Bill Failed.";
                $resp['error']=$this->lastErrorMsg();
            }
        }
        return json_encode($resp);
    }
    function delete_bill(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `bill_list` where bill_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['bill_flashdata']['type'] = 'success';
            $_SESSION['bill_flashdata']['msg'] = 'Bill successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$action = new Actions();
switch($a){
    case 'login':
        echo $action->login();
    break;
    case 'customer_login':
        echo $action->customer_login();
    break;
    case 'logout':
        echo $action->logout();
    break;
    case 'customer_logout':
        echo $action->customer_logout();
    break;
    case 'save_admin':
        echo $action->save_admin();
    break;
    case 'delete_admin':
        echo $action->delete_admin();
    break;
    case 'delete_connection':
        echo $action->delete_connection();
    break;
    case 'update_credentials':
        echo $action->update_credentials();
    break;
    case 'save_client':
        echo $action->save_client();
    break;
    case 'delete_client':
        echo $action->delete_client();
    break;
    case 'save_settings':
        echo $action->save_settings();
    break;
    case 'save_connection':
        echo $action->save_connection();
    break;
    case 'delete_connection':
        echo $action->delete_connection();
    break;
    case 'get_previous':
        echo $action->get_previous();
    break;
    case 'save_bill':
        echo $action->save_bill();
    break;
    case 'delete_bill':
        echo $action->delete_bill();
    break;
    default:
    // default action here
    break;
}