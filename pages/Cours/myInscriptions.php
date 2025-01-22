<?php 

session_start();

include_once __DIR__."/../../src/Models/Enseignant.php";
include_once __DIR__."/../../src/Models/Validator.php";

Validator::validateLogedInUser();
Validator::validateEnseignant();

$id_enseignant = $_SESSION['user_id'] ?? null;

$enseignant = new Enseignant();
$inscriptions = $enseignant->getCoursInscriptions($id_enseignant);

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

        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">My Inscriptions</h1>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des inscriptions</h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Etudiant</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Email</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">ID Cour</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Titre</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Date Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($inscriptions->num_rows > 0) {
                                while($row = $inscriptions->fetch_assoc()) {
                                    echo "<tr class='border-b'>";
                                    echo "<td class='py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['user_name']}</td>";
                                    echo "<td class='py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['email']}</td>";
                                    echo "<td class='py-3 px-4 text-left text-sm font-medium text-gray-600'><a class='cursor-pointer hover:text-blue-500 hover:decoration-underline' href='./cour.php?id={$row['id_cour']}'>{$row['id_cour']}</a> </td>";
                                    echo "<td class='py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['titre']}</td>";
                                    echo "<td class='py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['inscriptionDate']} </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-2'>No inscriptions found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
    </section>

    

</body>

</html>