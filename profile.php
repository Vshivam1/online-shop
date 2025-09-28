<?php
session_start();
include("db.php"); // adjust path if needed

// Check if user is logged in
if(!isset($_SESSION['uid'])) {
    header("Location: index.php");
    exit();
}

// Get logged-in user ID
$user_id = $_SESSION['uid'];

// Fetch user details
$sql = "SELECT user_id, first_name, last_name, email, mobile
        FROM user_info
        WHERE user_id = '$user_id'";

$result = mysqli_query($con, $sql);

if($row = mysqli_fetch_assoc($result)) {
    $first_name = $row['first_name'];
    $last_name  = $row['last_name'];
    $email      = $row['email'];
    $mobile     = $row['mobile'];
} else {
    echo "Profile not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link href="style/css/bootstrap.min.css" rel="stylesheet">
  <script src="style/js/jquery.min.js"></script>
  <script src="style/js/bootstrap.min.js"></script>
  <style>
    body {
      background: linear-gradient(135deg, #d30d0dff 0%, #dbe2c3ff 100%);
      font-family: 'Segoe UI', sans-serif;
    }

    .profile-container {
      max-width: 800px;
      margin: 80px auto;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
    }

    .profile-header {
      background-color: #8b3f3f;
      padding: 30px;
      border-radius: 15px;
      color: #fff;
      margin-bottom: 20px;
    }

    .profile-header img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 4px solid #fff;
      margin-bottom: 15px;
    }

    .profile-header h2 {
      font-weight: 600;
      margin-bottom: 5px;
    }

    .profile-header p {
      margin-bottom: 10px;
    }

    .profile-info table {
      width: 100%;
      text-align: left;
    }

    .profile-info th {
      width: 30%;
      padding: 8px;
      color: #555;
      font-weight: bold;
    }

    .profile-info td {
      padding: 8px;
      color: #000;
    }

    .btn-edit {
      margin-top: 20px;
      border-radius: 25px;
      padding: 10px 25px;
      background-color: #8b3f3f;
      color: whitesmoke;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }

    .btn-edit:hover {
      background-color: #b44a4a;
    }

  </style>
</head>
<body>

  <div class="profile-container">
    <div class="profile-header">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
      <h2><?php echo ucfirst($first_name)." ".ucfirst($last_name); ?></h2>
      <p>👤 Member since 2025</p>
    </div>

    <div class="profile-info">
      <table class="table">
        <tr><th>First Name</th><td><?php echo $first_name; ?></td></tr>
        <tr><th>Last Name</th><td><?php echo $last_name; ?></td></tr>
        <tr><th>Email</th><td><?php echo $email; ?></td></tr>
        <tr><th>Mobile</th><td><?php echo $mobile; ?></td></tr>
      </table>

      <button class="btn-edit" onclick="window.location.href='edit_profile.php'">Edit Profile</button>
       <button class="btn-edit" style="background-color: gray;" onclick="window.location.href='index.php'">Close</button>
    </div>
  </div>

</body>
</html>
