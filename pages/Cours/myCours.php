<?php 

session_start();

include_once __DIR__."/../../src/Models/Etudiant.php";
include_once __DIR__."/../../src/Models/Validator.php";

Validator::validateLogedInUser();
Validator::validateEtudiant();

$id_etudiant = $_SESSION['user_id'] ?? null;

$etudiant = new Etudiant();
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total = $etudiant->getEtudiantCoursCount($id_etudiant);
$numOfPages = ceil($total / $limit);
$cours = $etudiant->getEtudiantCours($id_etudiant, $limit, $offset);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['deleteCour'])){

        Validator::validateCsrf();

        $id_cour = (int) $_POST['id_cour'];
        $userRole = $_SESSION['user_role'] ?? null;

        $cour = new Cour();
        $cour->setIdCour($id_cour);
        $cour->deleteCour( $userRole);
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
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">My Cours</h1>

        
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