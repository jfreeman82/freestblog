<?php
namespace freest\blog\mvc\model\admin;

use freest\blog\mvc\model\admin\AdminModel as AdminModel;
use freest\blog\modules\User as User;
use freest\modules\DB\DBC as DBC;

/**
 * Description of UserModel
 *
 * @author myrmidex
 */
class UserAdminModel extends AdminModel 
{
    // arraytable_all_users
    public function arraytable_all_users(): Array 
    {
        $dbc = new DBC();;
        $sql = "SELECT id FROM users ORDER BY gendate,username DESC;";
        $q = $dbc->query($sql) or die("ERROR Model - ".$dbc->error());
        $data = array();
        $data[] = array(
                array('value' => 'id',        'class' => 'col-lg-2'),
                array('value' => 'username',  'class' => 'col-lg-8'),
                array('value' => 'actions',   'class' => 'col-lg-2')
              );
        
        while ($row = $q->fetch_assoc()) {
            $uid = $row['id'];
            $user = new User($uid);
            $action = ' 
            <a href="'.ADMIN_URL.'user/'. $uid .'/edit/">  Edit  </a>&nbsp;
            <a href="'.ADMIN_URL.'user/'. $uid .'/delete/">Delete</a>';
            $username = '<a href="'.ADMIN_URL.'user/'.$uid.'/">'.$user->getUsername().'</a>';
            $data[] = array($uid, $username,$action);
        }    
        $out['title'] = 'Users';
        $out['table-class'] = 'table table-bordered';
        $out['data'] = $data;
        $out['footer'] = '  
      <a href="'.ADMIN_URL.'user/new" class="btn btn-primary">Add New User</a>';
    
        return $out;
    }
    
    public function user(int $uid): User 
    {
        return new User($uid);
    }
    
    /* Form Processors
     * 
     *  Already in the 'new' style, with the typed Array output
     */
    public function fp_userNew(): Array 
    {
        if (filter_input(INPUT_POST, 'unform') == "go") {
            $username = filter_input(INPUT_POST, 'un_username');
            $password1 = filter_input(INPUT_POST, 'un_password1');
            $password2 = filter_input(INPUT_POST, 'un_password2');
            $email = filter_input(INPUT_POST, 'un_email');
            if ($username == "" || $email == "" || $password1 == "") {
                return array('status' => 'warning', 'warning' => 'Some fields were empty');
            }
            if ($password1 != $password2) {
                return array('status' => 'warning', 'warning' => 'Passwords do not match.');
            }
            $pwd = hash('sha256',$password1);
            $sql = "INSERT INTO users (username,password,email,gendate) 
                    VALUES ('$username','$pwd','$email',NOW());";
            $dbc = new DBC();
            if ($dbc->query($sql)) {
                return array('status' => '1');
            }
            else {
                return array('status' => 'warning', 'warning' => $dbc->error());
            }
        }
        else {
            return array('status' => '0');
        }
    }
    
    public function fp_userEdit(User $user): Array
    {
        if (filter_input(INPUT_POST, 'ueform') == "go") {
            $uid = $user->id();
            $username = filter_input(INPUT_POST, 'ue_username');
            $password1 = filter_input(INPUT_POST, 'ue_password1');
            $password2 = filter_input(INPUT_POST, 'ue_password2');
            $email = filter_input(INPUT_POST, 'ue_email');
            if ($username == "" || $email == "" || $password1 == "") {
                return array('status' => 'warning', 'warning' => 'Some fields were empty');
            }
            if ($password1 != $password2) {
                return array('status' => 'warning', 'warning' => 'Passwords do not match.');
            }
            $pwd = hash('sha256',$password1);
            $sql = "UPDATE users 
                    SET username = '$username', password = '$pwd', email = '$email'
                    WHERE id = '$uid';";
            $dbc = new DBC();
            if ($dbc->query($sql)) {
                return array('status' => '1');
            }
            else {
                return array('status' => 'warning', 'warning' => $dbc->error());
            }
        }
        else {
            return array('status' => '0');
        }
    }
    
    public function fp_userDelete(User $user): Array
    {
        if (filter_input(INPUT_POST, 'udform') == "go") {
            $uid = $user->id();
            $sql = "DELETE FROM users WHERE id = '$uid';";
            $dbc = new DBC();
            if ($dbc->query($sql)) {
                return array('status' => '1');
            }
            else {
                return array('status' => 'warning', 'warning' => $dbc->error());
            }
        }
        else {
            return array('status' => '0');
        }
    }
}
