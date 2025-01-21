<?php 

session_start();

include_once __DIR__."/../../src/Models/Category.php";
include_once __DIR__."/../../src/Models/Enseignant.php";
include_once __DIR__."/../../src/Models/Cour.php";
include_once __DIR__."/../../src/Models/Tag.php";
include_once __DIR__."/../../src/Models/Validator.php";

Validator::validateLogedInUser();
Validator::validateEnseignant();

$id_cour = (int) $_GET['id'];
$cour = new Cour();
$cour->setIdCour($id_cour);
$cour_infos = $cour->getCour();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['updateCour'])){

        Validator::validateCsrf();

        $id_cour = (int) $_POST['id_cour'];
        $titre = trim($_POST['titre']);
        $description = trim($_POST['description']);
        $contenu = $_FILES['contenu'];
        $oldContenuPath = $cour_infos['contenu_path'];
        $oldContenuType = $cour_infos['contenu_type'];
        $tags = $_POST['tags'];
        $id_category = $_POST['id_category'];
        $id_enseignant = $_SESSION['user_id'] ?? null;

        $enseignant = new Enseignant();
        $enseignant->updateCour($id_cour, $titre, $description, $contenu, $oldContenuPath, $oldContenuType, $tags, $id_category, $id_enseignant);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$categories = new Category();
$categories = $categories->getCategories();

$tags = new Tag();
$tags = $tags->getTags();

$titleErr = $_SESSION["titleErr"] ?? null;
$descriptionErr = $_SESSION["descriptionErr"] ?? null;
$contenuErr = $_SESSION["contenuErr"] ?? null;
$categoryErr = $_SESSION["categoryErr"] ?? null;
$tagsErr = $_SESSION["tagsErr"] ?? null;
unset($_SESSION["titleErr"],$_SESSION["descriptionErr"],$_SESSION["contenuErr"],$_SESSION["categoryErr"],$_SESSION["tagsErr"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Cour</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <?php include_once __DIR__."/../../layouts/header.php";?>


    <div class="min-h-screen py-32 flex items-center justify-center transform transition-all duration-300 hover:scale-105">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-xl">

            
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Modifier un Cour</h1>

         
            <form method="POST" enctype="multipart/form-data">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="id_cour" value="<?php if ($cour_infos) echo $cour_infos['id_cour']; ?>">

                <div class="mb-4">
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre de le Cour</label>
                    <input type="text" id="titre" name="titre" placeholder="Entrez le titre" required value="<?php if ($cour_infos) echo $cour_infos['titre']; ?>"
                        class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php if($titleErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$titleErr</p><br>" ?>
                </div>

               
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description du Cour</label>
                    <textarea id="description" name="description" rows="6" placeholder="Entrez la description ici" required
                        class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"><?php if ($cour_infos) echo $cour_infos['description']; ?></textarea>
                    <?php if($descriptionErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$descriptionErr</p><br>" ?>
                </div>

        
                <div class="mb-6">
                    <label for="cur_contenu" class="block text-sm font-medium text-gray-700">Current Contenu</label>
                    <?php
                        if ($cour_infos['contenu_type'] === 'video') {
                            echo "
                                <video controls src='../../contenus/".$cour_infos['contenu_path']."' id='cur_contenu' class='mt-2 block w-full px-4 py-2 border rounded-lg'></video>
                            ";
                        } else {
                            echo "
                                <iframe height='600px' src='../../contenus/".$cour_infos['contenu_path']."' id='cur_contenu' class='mt-2 block w-full px-4 py-2 border rounded-lg'></iframe>
                            ";
                        }
                        
                    ?>
                </div>

                
                <div class="mb-6">
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
                    <input type="file" accept=".pdf, .mp4" id="contenu" name="contenu" placeholder="Entrez le chemin de l'image"  
                        class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php if($contenuErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$contenuErr</p><br>" ?>
                </div>

                <div class="mb-6">
                    <label for="id_category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="id_category" name="id_category" required class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option>-- Choisire votre category --</option>
                        <?php
                        if ($categories->num_rows > 0) {
                            while ($row = $categories->fetch_assoc()) {
                                if ($row["id_category"] == $cour_infos['id_category']) {
                                    echo "
                                        <option selected value='{$row["id_category"]}'>{$row["category_name"]}</option>
                                    ";
                                } else {
                                    echo "
                                        <option value='{$row["id_category"]}'>{$row["category_name"]}</option>
                                    ";
                                }
                            }
                        }
                        ?>
                    </select>
                    <?php if($categoryErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$categoryErr</p><br>" ?>
                </div>

                                
                <div class="mb-6">
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <select multiple id="tags" name="tags[]" class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option>-- Choisire votre tags --</option>
                        <?php
                        if ($tags->num_rows > 0) {
                            while ($row = $tags->fetch_assoc()) {
                                echo "
                                    <option value='{$row["id_tag"]}'>{$row["tag_name"]}</option>
                                ";
                            }
                        }
                        ?>
                    </select>
                    <?php if($tagsErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$tagsErr</p><br>" ?>
                </div>
                
                <div class="text-center">
                    <button id="updateCour" name="updateCour" type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-medium text-lg rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Modifier le Cour
                    </button>
                </div>
            </form>

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
