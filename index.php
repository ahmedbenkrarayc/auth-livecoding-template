<?php

require './utils/db.php';
require './guards/authGuard.php';

if(!isAuth('guest')){
    header('Location: ./'.$_COOKIE['user_role'].'/dashboard.php');
}

$emailError = '';
$passwordError = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['email'])){
        $emailError = '';
    }else{
        $emailError = 'Email is required !';
    }

    if(isset($_POST['password'])){
        $passwordError = '';
    }else{
        $passwordError = 'Password is required !';
    }

    if(isset($_POST['email']) && isset($_POST['password'])){
        $stmt = $conn->prepare("SELECT id, role, password FROM user WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->bind_result($id, $res_role, $res_password);

        if($stmt->fetch()){
            $emailError = '';
            if(md5($_POST['password']) == $res_password){
                $passwordError = '';
                //correct 
                setcookie('user_id', $id, time() + 24 * 60 * 60, '/');
                setcookie('user_role', $res_role, time() + 24 * 60 * 60, '/');
                //reload page
                header('Location: '.$_SERVER['PHP_SELF']);
            }else{
                $passwordError = 'Wrong password';
            }
        }else{
            $emailError = 'There\'s nouser with this email !';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="bg-gray-50 dark:bg-gray-800">
        <div class="flex min-h-[80vh] flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="text-center sm:mx-auto sm:w-full sm:max-w-md">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    Sign in
                </h1>
            </div>
            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-8 sm:rounded-lg sm:px-10 sm:pb-6 sm:shadow">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email address /
                                Username</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="text" data-testid="username" required=""
                                    class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-300 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 sm:text-sm"
                                    value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
                            </div>
                            <span class="text-red-500 text-xs"><?php echo $emailError ?></span>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" data-testid="password"
                                    autocomplete="current-password" required=""
                                    class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-300 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 sm:text-sm"
                                    value="">
                            </div>
                            <span class="text-red-500 text-xs"><?php echo $passwordError ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember_me" type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:text-white dark:border-gray-600 dark:focus:ring-indigo-400 disabled:cursor-wait disabled:opacity-50">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-white">Remember me</label>
                            </div>
                        </div>
                        <div>
                            <button data-testid="login" type="submit"
                                class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-indigo-700 dark:border-transparent dark:hover:bg-indigo-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-2 disabled:cursor-wait disabled:opacity-50">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>