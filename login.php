

<?php
session_start();
include 'db_connect.php';

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if($password === $user['password']){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: students.php");
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body { font-family: Arial; background:#eef2f7; display:flex; justify-content:center; align-items:center; height:100vh; }
.form-container { background:#fff; padding:30px 40px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.2); width:320px; }
h2 { text-align:center; color:#333; margin-bottom:20px; }
input[type=text], input[type=password] { width:100%; padding:10px; margin:8px 0; border-radius:6px; border:1px solid #ccc; }
button { width:100%; padding:10px; background:#4CAF50; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:bold; }
button:hover { background:#45a049; }
.error { color:red; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>
<div class="form-container">
<h2>Login</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
</div>
</body>
</html>