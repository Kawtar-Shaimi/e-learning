<?php 

session_start();

include_once __DIR__."/../../../src/Models/Tag.php";
include_once __DIR__."/../../../src/Models/Validator.php";

Validator::validateAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['createTag'])){

        Validator::validateCsrf();

        $tag_name = trim($_POST['tag_name']);

        $tag = new Tag();
        $tag->setTagName($tag_name);
        $tag->createTag();
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$tagNameErr = $_SESSION["tagNameErr"] ?? null;
unset($_SESSION["tagNameErr"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Tag</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <div class="flex min-h-screen">
        <?php include_once "../../../layouts/sidebar.php";?>

        <div class="flex-1 p-6">
            

            <div class="mt-6 flex justify-center items-center">
               <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-xl">
                    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Ajouter un tag</h1>
                    <form method="POST">

                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                        <div class="mb-4">
                            <label for="tag_name" class="block text-sm font-medium text-gray-700">Nom du tag</label>
                            <input type="text" id="tag_name" name="tag_name" placeholder="Entrez le nom" required
                                class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <?php if($tagNameErr) echo "<p class='text-red-600 text-sm lg:text-[15px]'>$tagNameErr</p><br>" ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="createTag"
                                class="px-6 py-3 bg-blue-600 text-white font-medium text-lg rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Ajouter le tag
                            </button>
                        </div>
                    </form>

                </div>
            </div>
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
