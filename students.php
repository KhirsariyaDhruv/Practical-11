students.php

<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

// Check if logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Add student
if(isset($_POST['add'])){
    $name = trim($_POST['name']);
    $roll = trim($_POST['roll_no']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);

    $stmt = $conn->prepare("INSERT INTO students (name, roll_no, email, course) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$name,$roll,$email,$course);
    $stmt->execute();
}

// Delete student
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
}

// Search
$search = '';
if(isset($_GET['search'])){
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ?");
    $like = "%".$search."%";
    $stmt->bind_param("s",$like);
} else {
    $stmt = $conn->prepare("SELECT * FROM students");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Management</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #eef2f7;
    margin: 0;
    padding: 0;
}
.container {
    width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
h2 { text-align:center; color:#333; margin-bottom:20px; }
form { margin-bottom:20px; display:flex; flex-wrap: wrap; gap:10px; align-items:center; }
input[type=text], input[type=email] { padding:8px; border-radius:5px; border:1px solid #ccc; flex:1; }
button { padding:8px 15px; border:none; border-radius:5px; background:#4CAF50; color:#fff; font-weight:bold; cursor:pointer; }
button:hover { background:#45a049; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { border:1px solid #ccc; padding:8px; text-align:left; }
th { background:#f2f2f2; }
a { color:red; text-decoration:none; }
a:hover { text-decoration:underline; }
.search-reset { display:flex; gap:10px; align-items:center; margin-bottom:10px; }
.logout { text-align:right; margin-top:15px; }
.logout a { background:#ff4d4d; color:#fff; padding:6px 12px; border-radius:5px; text-decoration:none; }
.logout a:hover { background:#e60000; }
</style>
</head>
<body>
<div class="container">
<h2>Student Management</h2>

<!-- Search Form -->
<form method="GET" class="search-reset">
<input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
<button type="submit">Search</button>
<a href="students.php"><button type="button">Reset</button></a>
</form>

<!-- Add Student Form -->
<form method="POST">
<input type="text" name="name" placeholder="Name" required>
<input type="text" name="roll_no" placeholder="Roll No" required>
<input type="email" name="email" placeholder="Email">
<input type="text" name="course" placeholder="Course">
<button type="submit" name="add">Add Student</button>
</form>

<!-- Student Table -->
<table>
<tr>
<th>ID</th><th>Name</th><th>Roll No</th><th>Email</th><th>Course</th><th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['roll_no']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['course']; ?></td>
<td><a href="students.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

<div class="logout">
<a href="logout.php">Logout</a>
</div>
</div>
</body>
</html>