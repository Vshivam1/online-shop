<?php
include("../db.php");

// --- Delete user if action=delete ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // sanitize input

    // safer delete query using prepared statements
    $stmt = $con->prepare("DELETE FROM user_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully!');window.location.href='manage_users.php';</script>";
    } else {
        echo "Error deleting user!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style/css/bootstrap.min.css" rel="stylesheet">
<link href="style/css/k.css" rel="stylesheet">
<script src="style/js/jquery.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>

<div class="container-fluid">
<?php include("include/side_bar.php"); ?>
<div class="col-sm-9" style="margin-left:10px"> 
    <div class="panel-heading" style="background-color:#c4e17f">
        <h1>Manage User</h1>
    </div><br>

    <div style="overflow-x:scroll;">
        <table class="table table-bordered table-hover table-striped" style="font-size:18px">
            <tr>
                <th>User Email</th>
                <th>User Password</th>
                <th><a href="add_user.php">User delete</a></th>
            </tr>    
            <?php 
            $result = mysqli_query($con,"SELECT user_id, email, password FROM user_info") or die("query incorrect");

            while (list($user_id, $user_email, $user_password) = mysqli_fetch_array($result)) {
                echo "<tr>
                        <td>$user_email</td>
                        <td>$user_password</td>
                        <td>
                        
                            <a href='manage_users.php?user_id=$user_id&action=delete' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                        </td>
                      </tr>";
            }
            mysqli_close($con);
            ?>
        </table>
    </div>    
</div>
</div>
<?php include("include/js.php"); ?>
</body>
</html>
