<?php
session_start();
include("db.php");

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid'];

$sql = "SELECT first_name, last_name, email, mobile FROM user_info WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = ucfirst(strtolower(trim($_POST['first_name'])));
    $last_name  = ucfirst(strtolower(trim($_POST['last_name'])));
    $mobile     = trim($_POST['mobile']);

    if (!preg_match("/^[A-Za-z]+$/", $first_name)) {
        $error = "First name must contain only letters.";
    } elseif (!preg_match("/^[A-Za-z]+$/", $last_name)) {
        $error = "Last name must contain only letters.";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $error = "Mobile number must be exactly 10 digits.";
    } else {
        $update_sql = "UPDATE user_info SET first_name=?, last_name=?, mobile=? WHERE user_id=?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("sssi", $first_name, $last_name, $mobile, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: edit_profile.php?updated=1");
            exit();
        } else {
            $error = "Error updating profile.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body {
            background: linear-gradient(135deg, #d30d0dff 0%, #dbe2c3ff 100%);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            width: 420px;
            margin: 80px auto;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 12px;
            width: 48%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-update { background: #007bff; color: #fff; }
        .btn-cancel { background: #dc3545; color: #fff; text-align: center; text-decoration: none; }

        /* ✅ Popup toast style */
        .toast {
            visibility: hidden;
            min-width: 300px;
            background: #28a745;
            color: white;
            text-align: center;
            border-radius: 8px;
            padding: 15px;
            position: fixed;
            top: 20px;
            right: -350px;
            z-index: 1000;
            transition: right 0.6s ease-in-out, visibility 0.6s;
        }
        .toast.show {
            visibility: visible;
            right: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Profile</h2>

    <?php if (isset($error)): ?>
        <p style="color:red; text-align:center;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required pattern="[A-Za-z]+" title="Only letters allowed">
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required pattern="[A-Za-z]+" title="Only letters allowed">
        <input type="email" value="<?= htmlspecialchars($user['email']); ?>" disabled>
        <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']); ?>" required pattern="[0-9]{10}" maxlength="10" title="Enter 10-digit mobile number">
        
        <div class="btn-group">
            <button type="submit" class="btn btn-update">Update</button>
            <a href="index.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<!-- ✅ Popup toast -->
<div id="toast" class="toast"><?= $_SESSION['success'] ?? '' ?></div>

<script>
<?php if (isset($_GET['updated'])): ?>
    let toast = document.getElementById("toast");
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
        window.location.href = "index.php"; // ✅ redirect to index after 2 sec
    }, 2000);
<?php unset($_SESSION['success']); endif; ?>
</script>
</body>
</html>
