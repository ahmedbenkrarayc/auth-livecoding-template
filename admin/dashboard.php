<?php
require './../guards/authGuard.php';

if(!isAuth('admin')){
    header('Location: ./../index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex justify-center items-center">
    <div class="text-[30px] *:text-center">
        <h1>Admin</h1>
        <h2>Welcome to your dashboard</h2>
        <a href="./../utils/logout.php">logout</a>
    </div>
</body>
</html>