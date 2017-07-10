<?php
require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
//chkAdmin();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Users</title>
    </head>
    <body>
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
                /*********************************************************************************************/
                //include("../includes/header.php");
                echo '<table>';
                if (isset($return)) {
                    echo "<tr>
                                  <th>Username</th>
					              <th>email</th>
					              <th>Phone</th>
					              <th>Approved?</th>
					              <th>Admin?</th>
                                  <th>Time</th>
                          </tr>";
                    foreach ($return as $user) {
                        echo "<tr>
					              <td>{$user['username']}</td>
					              <td>{$user['email']}</td>
					              <td>{$user['phone']}</td>
					              <td>{$user['is_approved']}</td>
					              <td>{$user['is_admin']}</td>
                                  <td>{$user['createTime']}";
	    				    if (!$user['is_approved']) {echo "<td><a href=\"admin.php?id={$user['id']}&op=approve\" class=\"button\">Approve</a></td>";}
					        if (!$user['is_admin']) {echo "<td><a href=\"admin.php?id={$user['id']}&op=setadmin\" class=\"button\">Make Admin</a></td>";}
					        else {echo "<td><a href=\"admin.php?id={$user['id']}&op=unsetadmin\" class=\"button\">Remove Admin</a></td>";}
					          	  echo "<td><a href=\"admin.php?id={$user['id']}&op=delete\" class=\"button\">Delete</a></td>
				              </tr>";
                    }
                }
                echo '</table>';
                //include("../includes/header.php");
                /*********************************************************************************************/
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
        </body>
</html>