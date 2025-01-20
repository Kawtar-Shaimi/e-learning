<?php 

session_start();

include_once __DIR__."/../../src/Models/Cour.php";
include_once __DIR__."/../../src/Models/Category.php";
include_once __DIR__."/../../src/Models/Etudiant.php";
include_once __DIR__."/../../src/Models/Tag.php";
include_once __DIR__."/../../src/Models/Validator.php";

Validator::validateLogedInUser();

$id_cour = (int) $_GET['id'];

$cour = new Cour();
$cour_infos = $cour->getCour($id_cour);

$tags = $cour->getCourTags($id_cour);

$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['inscrit'])){

        Validator::validateCsrf();

        $id_cour = (int) $_POST['id_cour'];
        $id_user = (int) $_POST['id_user'];

        $etudiant = new Etudiant();
        $etudiant->inscriptionsToCours($id_user, $id_cour);
    }

}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = $_SESSION["message"] ?? null;
unset($_SESSION["contentErr"],$_SESSION["message"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cour</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    
    <?php include_once __DIR__."/../../layouts/header.php";?>


    <div class="container mx-auto py-8 px-4">
            <?php 
            if($message){
                echo "
                    <div class='max-w-3xl mx-auto bg-green-600 rounded-lg my-5 py-5 ps-5'>
                        <p class='text-white font-bold'>$message</p>
                    </div>
                ";
            }
            ?>
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-xl">

            <article>
                <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($cour_infos["titre"]) ?></h1>
                <p class="text-lg text-gray-700 mb-6"><?php echo $cour_infos["user_name"] ?></p>
                <p class="text-lg text-gray-700 mb-6 break-words"><?php echo htmlspecialchars($cour_infos["description"]) ?></p>
                <?php
                    if ($cour_infos['contenu_type'] === 'video') {
                        echo "
                            <video controls src='../../contenus/".$cour_infos['contenu_path']."' id='cur_contenu' class='w-full h-[700PX] object-cover rounded-lg mb-6'></video>
                        ";
                    } else {
                        echo "
                            <iframe src='../../contenus/".$cour_infos['contenu_path']."' id='cur_contenu' class='w-full h-[700PX] object-cover rounded-lg mb-6'></iframe>
                        ";
                    }
                    
                ?>
                <p class="text-lg text-gray-700 mb-6"><?php echo $cour_infos["category_name"] ?></p>
            </article>

            <ul class="list-disc my-10">
                Tags
                <?php
                if ($tags->num_rows > 0) {
                    while ($row = $tags->fetch_assoc()) {
                        echo "
                            <li>{$row["tag_name"]}</li>
                        ";
                    }
                }
                ?>
            </ul>

            <?php
                $etudiant = new Etudiant();
                $is_inscrit = $etudiant->checkInscription($userId, $id_cour);
                if (!$is_inscrit) {
                    if($userRole === "etudiant"){
                        echo "
                        <form method='POST' class='mb-8'>
                            <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                            <input type='hidden' value='". htmlspecialchars($cour_infos["id_cour"]) . "' name='id_cour'>
                            <input type='hidden' value='$userId' name='id_user'>
                            <button name='inscrit' class='bg-orange-500 text-white font-bold text-xl text-center rounded-xl py-2 px-4' >Inscrit</button>
                        </form>
                    ";
                    }
                }
            ?>

        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 KSBlog. Tous droits réservés.</p>
        </div>
    </footer>

</body>

</html>