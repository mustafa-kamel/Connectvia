<?php
require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
define("TITLE", "Users");
chkAdmin();
?>
<?php include('header.php');?>        
<?php include('sidebar.php');?>
        <div class="container" style="width: 96%; margin: 50px 300px 70px 50px;">
        <?php
        $userob = new UsersModel(); $msg = '';
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET) && !empty($_GET)) {
                $id = ''; $type = '';
                if (isset($_GET['type']) && !empty($_GET['type'])) {
                    $type = strval($_GET['type']);
                } else {
                    header("HTTP/1.0 503 Internal server errors");
                    $msg .= "Undetermined type! ";
                }
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = intval($_GET['id']);
                }
                switch ($type){
                    //case 'user':$return = $userob->getById($id);    break;
                    case 'new': $return = $userob->getNew();        break;
                    case 'admin':$return= $userob->getAdmin();      break;
                    case 'all':  $return= $userob->get();           break;
                    default : $msg .= "Undefined type! ";
                }
                echo '<table class="table table-hover">';
                if (isset($return)) {
                    echo "<thead><tr>
                                  <th>Username</th>
					              <th>email</th>
					              <th>Phone</th>
					              <th>Approved?</th>
					              <th>Admin?</th>
                                  <th>Time</th>
                          </tr></thead>";
                    foreach ($return as $user) {
                        echo "<tbody><tr>
					              <th scope='row'>{$user['username']}</td>
					              <td>{$user['email']}</td>
					              <td>{$user['phone']}</td>
					              <td>{$user['is_approved']}</td>
					              <td>{$user['is_admin']}</td>
                                  <td>{$user['createTime']}";
	    				    if (!$user['is_approved']) {echo "<td class='bg-success'><a href=\"admin.php?id={$user['id']}&op=approve&type=$type\" class=\"button\">Approve</a></td>";}
					        if (!$user['is_admin']) {echo "<td class='bg-warning'><a href=\"admin.php?id={$user['id']}&op=setadmin&type=$type\" class=\"button\">Make Admin</a></td>";}
					        else {echo "<td><a href=\"admin.php?id={$user['id']}&op=unsetadmin&type=$type\" class=\"button\">Remove Admin</a></td>";}
					          	  echo "<td class='bg-danger'><a href=\"admin.php?id={$user['id']}&op=delete&type=$type\" class=\"button\">Delete</a></td>
				              </tr></tbody>";
                    }
                }
                echo '</table>';
            } else {
                header("HTTP/1.0 503 Internal server errors");
                $msg .= "No data sent! ";
            }
        } else {
            header("HTTP/1.0 503 Internal server errors");
            $msg .= "Unsupported method! ";
        }
        echo $msg;
        //System::RedirectTo("user.php");
        ?>       
</div>
<?php include('footer.php');?>