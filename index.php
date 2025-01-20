<?php 
session_start();

include_once __DIR__."/src/Models/Validator.php";
include_once __DIR__."/src/Models/Cour.php";

$id_user = $_SESSION['user_id'] ?? null;

$cours = new Cour();
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total = $cours->getTotalCours();
$numOfPages = ceil($total / $limit);
$cours = $cours->getPaginatedCours($limit, $offset);

if($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET"){

    $query = isset($_POST['searchCours']) ? trim($_POST['query']) : (
        $_GET['query'] ?? null
    );

    if ($query) {
        $cours = new Cour();
        $cours = $cours->getSearchCours($query, $limit, $offset);
        $numOfPages = ceil($cours->num_rows / $limit);
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours - KS-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

   
    <?php include_once __DIR__."/layouts/header.php";?>

    
    <section class="container mx-auto p-6 relative">

        <form class="flex h-10 my-10" method="post">
            <input required class="block w-10/12 px-4 py-2 border rounded-s-lg focus:outline-none focus:ring-2 focus:ring-blue-400" name="query" type="text">
            <button name="searchCours" class="block w-2/12 border cursor-pointer bg-blue-500 hover:bg-blue-700 text-white text-md font-bold rounded-e-lg transition duration-300" type="submit">
                Search
            </button>
        </form>

        <?php 
        if(isset($query)){
            echo "
                <h2 class='text-4xl font-bold text-gray-800 mb-8'>Search For '$query'</h2>
            ";
        }
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php
            if ($cours->num_rows > 0) {
                while ($row = $cours->fetch_assoc()) {
                    echo "
                        <div class='bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300'>
                            <div class='p-6'>
                                <h2 class='text-2xl font-semibold text-blue-600 mb-4'>" . htmlspecialchars($row["titre"]) . "</h2>
                                <p class='text-gray-700 mb-4 break-all'>". htmlspecialchars($row["description"]) ."
                                ";
                    if ($id_user) {
                        echo"<a href='./pages/Cours/cour.php?id=" . htmlspecialchars($row["id_cour"]) . "' class='text-blue-500 hover:underline font-semibold'> Lire le cour</a>";
                    }
                    echo"</p>
                                <div class='flex items-center justify-between px-4'>
                                    <p class='text-gray-700 mb-4'>". htmlspecialchars($row["user_name"]) ."</p>
                                    <p class='text-gray-700 mb-4'>". htmlspecialchars($row["category_name"]) ."</p>
                                </div>
                            </div>
                        </div>
                    ";
                }
            }else{
                if(isset($query)){
                    echo "
                        <p class='text-center min-h-[77vh] flex items-center justify-center text-4xl text-red-500 font-bold col-span-1 sm:col-span-2 lg:col-span-3'>Nothing Found For '$query'</p>
                    ";
                }else{
                    echo "
                        <p class='text-center min-h-[77vh] flex items-center justify-center text-4xl text-red-500 font-bold col-span-1 sm:col-span-2 lg:col-span-3'>No Cours Yet</p>
                    ";
                }
            }
            ?>

        </div>
    </section>

    <div class="w-5/6 my-10 mx-auto flex items-center justify-center">
    <?php

        $link = $query ? "?query=$query&page=" : "?page=";

        if ($page > 1) {
            echo "<a class='font-semibold py-4 px-5 border' href='$link" . ($page - 1) . "'>Previous</a> ";
        }

        for ($i = 1; $i <= $numOfPages; $i++) {
            if ($page === $i) {
                echo "<a class='font-semibold py-4 px-5 border border-black text-white bg-blue-500' href='$link$i'>$i</a> ";
            } else {
                echo "<a class='font-semibold py-4 px-5 border' href='$link$i'>$i</a> ";
            }
        }

        if ($page < $numOfPages) {
            echo "<a class='font-semibold py-4 px-5 border' href='$link" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>

    
    <footer class="bg-blue-600 text-white p-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 KS-Learning. Tous droits réservés.</p>
        </div>
    </footer>

</body>

</html>