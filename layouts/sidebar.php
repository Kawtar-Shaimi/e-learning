<?php

include_once __DIR__."/../src/Models/Admin.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['logout'])){
        $admin = new Admin();
        $admin->logout();
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="sidebar w-64 bg-blue-600 text-white p-6">
    <h2 class="text-2xl font-semibold mb-6">Admin Dashboard</h2>
    <ul>
    <li><a href="http://localhost/e-learning/pages/Admin/statistics.php" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Statistics</a></li>
    <li><a href="http://localhost/e-learning/pages/Admin/Users/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Utilisateurs</a></li>
        <li><a href="http://localhost/e-learning/pages/Admin/Cours/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Cours</a></li>
        <li><a href="http://localhost/e-learning/pages/Admin/Categories/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Categories</a></li>
        <li><a href="http://localhost/e-learning/pages/Admin/Tags/" class="block py-2 px-4 hover:bg-gray-700 rounded-lg">Tags</a></li>
        <li>
            <form class="w-full" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"] ?>">
                <button name="logout" class="w-full text-left block py-2 px-4 hover:bg-gray-700 rounded-lg">Logout</button>
            </form>
        </li>
    </ul>
</div>