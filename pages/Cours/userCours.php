<?php 

session_start();

include_once __DIR__."/../../src/Models/Cour.php";
include_once __DIR__."/../../src/Models/Enseignant.php";
include_once __DIR__."/../../src/Models/Validator.php";

Validator::validateLogedInUser();
Validator::validateEnseignant();

$id_enseignant = $_SESSION['user_id'] ?? null;

$enseignant = new Enseignant();
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total = $enseignant->getEnseignantCoursCount($id_enseignant);
$numOfPages = ceil($total / $limit);
$cours = $enseignant->getEnseignantCours($id_enseignant, $limit, $offset);


if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['deleteCour'])){

        Validator::validateCsrf();

        $id_cour = (int) $_POST['id_cour'];
        $userRole = $_SESSION['user_role'] ?? null;

        $cour = new Cour();
        $cour->setIdCour($id_cour);
        $cour->deleteCour($userRole);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

   
    <?php include_once "../../layouts/header.php";?>

    
    <section class="container mx-auto p-6 relative">
        <?php 
        if($message){
            echo "
                <div class='max-w-3xl mx-auto bg-green-600 rounded-lg my-5 py-5 ps-5'>
                    <p class='text-white font-bold'>$message</p>
                </div>
            ";
        }
        ?>

        <?php include_once "../../layouts/enseignantStatistics.php";?>

        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">My Cours</h1>

        <a href="./ajouterCour.php" class="block w-1/6 cursor-pointer my-10 bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">Ajouter un cour</a>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php
                if ($cours->num_rows > 0) {
                    while ($row = $cours->fetch_assoc()) {
                        echo "
                            <div class='bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300'>
                                <div class='p-6'>
                                    <h2 class='text-2xl font-semibold text-blue-600 mb-4'>" . htmlspecialchars($row["titre"]) . "</h2>
                                    <p class='text-gray-700 mb-4 break-all'>". htmlspecialchars($row["description"]) ."
                                        <a href='./cour.php?id=" . htmlspecialchars($row["id_cour"]) . "' class='text-blue-500 hover:underline font-semibold'> Lire le cour</a>
                                    </p>
                                    <div class='flex items-center justify-between px-4'>
                                        <p class='text-gray-700 mb-4'>". htmlspecialchars($row["user_name"]) ."</p>
                                        <p class='text-gray-700 mb-4'>". htmlspecialchars($row["category_name"]) ."</p>
                                    </div>
                                </div>
                                <div class='flex gap-2 px-2 py-2'>
                                    <a href='./updateCour.php?id=" . htmlspecialchars($row["id_cour"]) . "' class='text-blue-900 h-5 cursor-pointer'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' class='text-blue-900 h-5 cursor-pointer'>
                                            <path fill='currentColor' d='M570.1 128.1c-9.4-9.4-24.6-9.4-33.9 0L504 150.1 361.9 8 384 0c10.7 0 20.5 4.1 28.1 11.7l157.9 157.9c9.4 9.4 9.4 24.6 0 33.9l-22.1 22.1c-9.4 9.4-24.6 9.4-33.9 0L320 63.9 63.9 320 0 512l192-63.9 256-256-42.1-42.1c-9.4-9.4-9.4-24.6 0-33.9l22.1-22.1c9.4-9.4 24.6-9.4 33.9 0l42.1 42.1 256-256 42.1 42.1c9.4 9.4 9.4 24.6 0 33.9l-22.1 22.1z'/>
                                        </svg>
                                    </a>
                                    <form method='POST'>
                                        <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                        <input type='hidden' value='". htmlspecialchars($row["id_cour"]) . "' name='id_cour'>
                                        <button name='deleteCour' type='submit' class='text-blue-900 h-5 cursor-pointer'>
                                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' class='w-5 h-5'>
                                                <path fill='currentColor' d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        ";
                    }
                }else{
                    echo "
                        <p class='text-center min-h-[77vh] flex items-center justify-center text-4xl text-red-500 font-bold col-span-1 sm:col-span-2 lg:col-span-3'>No Cours Yet</p>
                    ";
                }
            ?>

        </div>
    </section>

    <div class="w-5/6 my-10 mx-auto flex items-center justify-center">
    <?php

        if ($page > 1) {
            echo "<a class='font-semibold py-4 px-5 border' href='?page=" . ($page - 1) . "'>Previous</a> ";
        }

        for ($i = 1; $i <= $numOfPages; $i++) {
            if ($page === $i) {
                echo "<a class='font-semibold py-4 px-5 border border-black text-white bg-blue-500' href='?page=$i'>$i</a> ";
            } else {
                echo "<a class='font-semibold py-4 px-5 border' href='?page=$i'>$i</a> ";
            }
        }

        if ($page < $numOfPages) {
            echo "<a class='font-semibold py-4 px-5 border' href='?page=" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 KSBlog. Tous droits réservés.</p>
        </div>
    </footer>

</body>

</html>