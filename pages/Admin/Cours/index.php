<?php

session_start();

include_once __DIR__."/../../../src/Models/Cour.php";
include_once __DIR__."/../../../src/Models/Validator.php";

Validator::validateAdmin();

$cours = new Cour();
$result = $cours->getCours();

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
    <title>Page d'Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">


        <?php include_once "../../../layouts/sidebar.php";?>

        <div class="flex-1 p-6">
            

            <?php 
            if($message){
                echo "
                    <div class='max-w-3xl mx-auto bg-green-600 rounded-lg my-5 py-5 ps-5'>
                        <p class='text-white font-bold'>$message</p>
                    </div>
                ";
            }
            ?>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des cours</h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">ID</th>
                            <th class="py-3 px-7 text-left text-sm font-medium text-gray-600">Titre</th>
                            <th class="py-3 px-7 text-left text-sm font-medium text-gray-600">Content Type</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID Category</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">ID Enseignant</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Date Creation</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Date Modification</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr class='border-b'>";
                                    echo "<td class='py-3 px-4 text-sm text-gray-800'><a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='./cour.php?id={$row['id_cour']}'>{$row['id_cour']}</a> </td>";
                                    echo "<td class='py-3 px-7 text-sm text-gray-800'> {$row['titre']} </td>";
                                    echo "<td class='py-3 px-7 text-sm text-gray-800'> {$row['contenu_type']} </td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['id_category']}</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['id_enseignant']}</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['dateCreation']}</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['dateModification']}</td>";
                                    echo "<td class='flex gap-2 px-2 py-2'>
                                            <form method='POST'>
                                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                                <input type='hidden' value='". htmlspecialchars($row["id_cour"]) . "' name='id_cour'>
                                                <button name='deleteCour' type='submit' class='text-red-500 hover:text-red-700 cursor-pointer'>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center py-2'>No cours found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
