<?php

require_once __DIR__ . '/../src/Models/User.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['logout'])){
        $user = new User();
        $user->logout();
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
?>

<header class="bg-blue-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        
        <div class="text-2xl font-bold flex space-x-2">
            <a href="http://localhost/e-learning">SukiLearning</a>
        </div>

        <nav>
            <ul class="flex space-x-6">
                <li><a href="http://localhost/e-learning" class="hover:text-gray-300">Home</a></li>
                <?php
                if($userId){
                    if ($userRole === "enseignant") {
                        echo "
                            <li><a href='http://localhost/e-learning/pages/Cours/userCours.php' class='hover:text-gray-300'>Cours</a></li>
                            <li><a href='http://localhost/e-learning/pages/Cours/myInscriptions.php' class='hover:text-gray-300'>My Inscriptions</a></li>
                        ";
                    } elseif ($userRole === "etudiant") {
                        echo "
                            <li><a href='http://localhost/e-learning/pages/Cours/myCours.php' class='hover:text-gray-300'>My Cours</a></li>
                        ";
                    }
                } 
                ?>
            </ul>
        </nav>

        <?php 
        if(!$userId){
            echo "
                <div class='space-x-4'>
                    <a href='http://localhost/e-learning/pages/Auth/login.php' class='bg-transparent border-2 border-white hover:bg-white hover:text-blue-600 text-white py-2 px-4 rounded-lg transition duration-300'>Login</a>
                    <a href='http://localhost/e-learning/pages/Auth/signup.php' class='bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300'>Sign Up</a>
                </div>
            ";
        }else{
            echo "
                <form method='POST'>
                    <input type='hidden' name='csrf_token' value='{$_SESSION["csrf_token"]}'>
                    <button name='logout' class='bg-transparent border-2 border-white hover:bg-white hover:text-blue-600 text-white py-2 px-4 rounded-lg transition duration-300'>Logout</button>
                </form>
            ";
        }
        ?>
        
    </div>
</header>