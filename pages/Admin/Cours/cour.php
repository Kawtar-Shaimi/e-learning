<?php 

session_start();

include_once __DIR__."/../../../src/Models/Cour.php";
include_once __DIR__."/../../../src/Models/Validator.php";

Validator::validateAdmin();

if(isset($_GET['id'])){

    $id_cour = (int) $_GET['id'];

    $cour = new Cour();
    $cour->setIdCour($id_cour);
    $cour_infos = $cour->getCour();
    $tags = $cour->getCourTags();

}

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

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo $cour_infos['titre']?></h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Titre</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['titre']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Description</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['description']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Contenu Type</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['contenu_type']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Contenu Path</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['contenu_path']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Enseignant</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['user_name']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Category</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['category_name']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Tags</td>
                        <td class="border border-gray-200 px-4 py-2">
                            <?php
                                if ($tags->num_rows > 0) {
                                    while($row = $tags->fetch_assoc()) {
                                        echo $row["tag_name"] . ", ";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de création</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['created_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Date de modification</td>
                        <td class="border border-gray-200 px-4 py-2"><?php echo $cour_infos['updated_at']?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">Actions</td>
                        <td class="flex gap-2 border border-gray-200 px-4 py-2">
                            <form method='POST'>
                                <input type='hidden' name='csrf_token' value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type='hidden' value="<?php echo $cour_infos['id_cour']?>" name='id_cour'>
                                <button type='submit' name='deleteCour' class='text-red-500 hover:text-red-700 cursor-pointer'>
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>