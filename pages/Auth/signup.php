<?php

session_start();

include_once __DIR__."/../../src/Models/Validator.php";
include_once __DIR__."/../../src/Models/User.php";

Validator::validateLogedOutUser();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['signup'])){

        Validator::validateCsrf();

        $name = trim($_POST['user_name']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $pass = trim($_POST['password']);
        $confirmPass = trim($_POST['confirm_password']);
        $remember = isset($_POST['remember']);

        $user = new User();
        $user->signup($name, $email, $role, $pass, $confirmPass);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nameErr = $_SESSION["nameErr"] ?? null;
$emailErr = $_SESSION["emailErr"] ?? null;
$roleErr = $_SESSION["roleErr"] ?? null;
$passErr = $_SESSION["passErr"] ?? null;
$confirmPassErr = $_SESSION["confirmPassErr"] ?? null;
unset($_SESSION["nameErr"],$_SESSION["emailErr"],$_SESSION["roleErr"],$_SESSION["passErr"],$_SESSION["confirmPassErr"])
?>

<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    
    <?php include_once "../../layouts/header.php";?>

   
    <div class="flex justify-center items-center min-h-screen">
    
        
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm transform transition-all duration-300 hover:scale-105">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">S'inscrire</h2>
            
            <form method="POST">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                
                <div class="mb-4">
                    <label for="user_name" class="block text-sm font-medium text-gray-600">Nom</label>
                    <input id="user_name" type="text"  name="user_name" placeholder="Entrez votre nom complet" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" >
                        <?php if($nameErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$nameErr</p><br>" ?>
                </div>

                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input id="email" type="email" name="email" placeholder="Entrez votre email" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105">
                    <?php if($emailErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$emailErr</p><br>" ?>
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Nom de la category</label>
                    <select id="role" name="role" required class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option>-- Choisisez Un Role --</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="etudiant">Etudiant</option>
                    </select>
                    <?php if($roleErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$roleErr</p><br>" ?>
                </div>

                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600">Mot de passe</label>
                    <input id="password" type="password"  name="password" placeholder="Entrez votre mot de passe" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105">
                    <?php if($passErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$passErr</p><br>" ?>
                </div>

                
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-600">Confirmez le mot de passe</label>
                    <input id="confirm_password" type="password"  name="confirm_password" placeholder="Confirmez votre mot de passe" class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105">
                    <?php if($confirmPassErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$confirmPassErr</p><br>" ?>
                </div>

                
                <button id="signup" name="signup" type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:scale-105">
                    S'inscrire
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="./login.php" class="text-sm text-blue-500 hover:underline transition duration-300 ease-in-out transform hover:scale-105">Déjà un compte ? Connectez-vous</a>
            </div>
        </div>

    </div>

</body>

</html>