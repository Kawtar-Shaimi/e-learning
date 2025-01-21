<?php

include_once __DIR__."/../../src/Models/Tag.php";
include_once __DIR__."/../../src/Models/Cour.php";
include_once __DIR__."/../../src/Models/Admin.php";
include_once __DIR__."/../../src/Models/Category.php";

$admin = new Admin();
$usersCount = $admin->getUsers()->num_rows;

$cour = new Cour();
$coursCount = $cour->getCours()->num_rows;

$category = new Category();
$categoriesCount = $category->getCategories()->num_rows;

$tag = new Tag();
$tagsCount = $tag->getTags()->num_rows;

$rolesCount  = $admin->getRolesCount();
$courCountByCat  = $admin->getCourCountByCat();
$topEnseignants  = $admin->getTopEnseignants();
$topCour  = $admin->getTopCour();
    
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


        <?php include_once "../../layouts/sidebar.php";?>

        <div class="flex-1 p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Utilisateurs</h3>
                        <p class="text-2xl font-bold text-blue-600"><?php echo $usersCount?></p>
                    </div>
                    <div class="p-4 bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-blue-600" viewBox="0 0 640 512">
                            <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z"/>
                        </svg>
                    </div>
                </div>


                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Cours</h3>
                        <p class="text-2xl font-bold text-yellow-600"><?php echo $coursCount?></p>
                    </div>
                    <div class="p-4 bg-yellow-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-yellow-600" viewBox="0 0 512 512">
                            <path d="M168 80c-13.3 0-24 10.7-24 24l0 304c0 8.4-1.4 16.5-4.1 24L440 432c13.3 0 24-10.7 24-24l0-304c0-13.3-10.7-24-24-24L168 80zM72 480c-39.8 0-72-32.2-72-72L0 112C0 98.7 10.7 88 24 88s24 10.7 24 24l0 296c0 13.3 10.7 24 24 24s24-10.7 24-24l0-304c0-39.8 32.2-72 72-72l272 0c39.8 0 72 32.2 72 72l0 304c0 39.8-32.2 72-72 72L72 480zM176 136c0-13.3 10.7-24 24-24l96 0c13.3 0 24 10.7 24 24l0 80c0 13.3-10.7 24-24 24l-96 0c-13.3 0-24-10.7-24-24l0-80zm200-24l32 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-32 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zm0 80l32 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-32 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zM200 272l208 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-208 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zm0 80l208 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-208 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
                        </svg>
                    </div>
                </div>


                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Tags</h3>
                        <p class="text-2xl font-bold text-red-600"><?php echo $tagsCount?></p>
                    </div>
                    <div class="p-4 bg-red-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-red-600" viewBox="0 0 448 512">
                            <path d="M181.3 32.4c17.4 2.9 29.2 19.4 26.3 36.8L197.8 128l95.1 0 11.5-69.3c2.9-17.4 19.4-29.2 36.8-26.3s29.2 19.4 26.3 36.8L357.8 128l58.2 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-68.9 0L325.8 320l58.2 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-68.9 0-11.5 69.3c-2.9 17.4-19.4 29.2-36.8 26.3s-29.2-19.4-26.3-36.8l9.8-58.7-95.1 0-11.5 69.3c-2.9 17.4-19.4 29.2-36.8 26.3s-29.2-19.4-26.3-36.8L90.2 384 32 384c-17.7 0-32-14.3-32-32s14.3-32 32-32l68.9 0 21.3-128L64 192c-17.7 0-32-14.3-32-32s14.3-32 32-32l68.9 0 11.5-69.3c2.9-17.4 19.4-29.2 36.8-26.3zM187.1 192L165.8 320l95.1 0 21.3-128-95.1 0z"/>
                        </svg>
                    </div>
                </div>


                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Categories</h3>
                        <p class="text-2xl font-bold text-purple-600"><?php echo $categoriesCount?></p>
                    </div>
                    <div class="p-4 bg-purple-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-purple-600" viewBox="0 0 512 512">
                            <path d="M40 48C26.7 48 16 58.7 16 72l0 48c0 13.3 10.7 24 24 24l48 0c13.3 0 24-10.7 24-24l0-48c0-13.3-10.7-24-24-24L40 48zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L192 64zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zM16 232l0 48c0 13.3 10.7 24 24 24l48 0c13.3 0 24-10.7 24-24l0-48c0-13.3-10.7-24-24-24l-48 0c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24l0 48c0 13.3 10.7 24 24 24l48 0c13.3 0 24-10.7 24-24l0-48c0-13.3-10.7-24-24-24l-48 0z"/>
                        </svg>
                    </div>
                </div>

            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Roles Count</h2>

                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Role</th>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($rolesCount->num_rows > 0) {
                                while($row = $rolesCount->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['role']} </td>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['roleCount']} </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center py-2'>No Roles Count found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Cour Count By Category</h2>

                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Category</th>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($courCountByCat->num_rows > 0) {
                                while($row = $courCountByCat->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['category_name']} </td>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['categoryCount']} </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center py-2'>No Cour Count By Category found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Enseignants</h2>

                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Enseignant</th>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Inscriptions Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($topEnseignants->num_rows > 0) {
                                while($row = $topEnseignants->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['user_name']} </td>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['inscriptionsCount']} </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center py-2'>No Top Enseignant found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Cour</h2>

                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">ID</th>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Cour</th>
                            <th class="border py-3 px-4 text-left text-sm font-medium text-gray-600">Etudiants Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($topCour->num_rows > 0) {
                                while($row = $topCour->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['id_cour']}</td>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['titre']}</td>";
                                    echo "<td class='border py-3 px-4 text-left text-sm font-medium text-gray-600'> {$row['etudiantsCount']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center py-2'>No Top Enseignant found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>