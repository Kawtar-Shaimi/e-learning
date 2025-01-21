<?php

    session_start();

    include_once __DIR__."/../../../src/Models/Admin.php";
    include_once __DIR__."/../../../src/Models/Validator.php";

    Validator::validateAdmin();

    $admin = new Admin();
    $users = $admin->getUsersByStatus("non_valide");

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(isset($_POST['deleteUser'])){

            Validator::validateCsrf();

            $id_user = (int) $_POST['id_user'];

            $admin = new Admin();
            $admin->deleteUser($id_user);
        }

        if(isset($_POST['activeUser'])){

            Validator::validateCsrf();

            $id_user = (int) $_POST['id_user'];

            $admin = new Admin();
            $admin->changeUserStatus("valide", $id_user);
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
            <div class='my-20 flex items-center justify-center gap-x-5'>
                <a href="./index.php" class='text-white font-bold bg-blue-600 rounded-lg p-5 border hover:border-blue-600 hover:bg-blue-300'>Users</a>
                <a href="./valideUsers.php" class='text-white font-bold bg-blue-600 rounded-lg p-5 border hover:border-blue-600 hover:bg-blue-300'>Valide Users</a>
                <a href="./nonValideUsers.php" class='text-white font-bold border border-blue-600 bg-blue-300 rounded-lg p-5'>Non Valide Users</a>
                <a href="./suspendUsers.php" class='text-white font-bold bg-blue-600 rounded-lg p-5 border hover:border-blue-600 hover:bg-blue-300'>Suspend Users</a>
            </div>
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des administrateurs</h2>
            
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">ID</th>
                            <th class="py-3 px-7 text-left text-sm font-medium text-gray-600">Name</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Email</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Password</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Role</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($users->num_rows > 0) {
                                while($row = $users->fetch_assoc()) {
                                    echo "<tr class='border-b'>";
                                    echo "<td class='py-3 px-4 text-sm text-gray-800'> {$row['id_user']} </td>";
                                    echo "<td class='py-3 px-7 text-sm text-gray-800'> {$row['user_name']} </td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'> {$row['email']}</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['password']}</td>";
                                    echo "<td class='py-3 px-6 text-sm text-gray-800'>{$row['role']}</td>";
                                    echo "<td class='flex gap-2 px-2 py-2'>
                                            <form method='POST'>
                                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                                <input type='hidden' value='". htmlspecialchars($row["id_user"]) . "' name='id_user'>
                                                <button name='deleteUser' type='submit' class='text-red-500 hover:text-red-700 cursor-pointer'>
                                                    Supprimer
                                                </button>
                                            </form>
                                            <form method='POST'>
                                                <input type='hidden' name='csrf_token' value='". htmlspecialchars($_SESSION['csrf_token']) . "'>
                                                <input type='hidden' value='". htmlspecialchars($row["id_user"]) . "' name='id_user'>
                                                <button name='activeUser' type='submit' class='text-green-500 hover:text-green-700 cursor-pointer'>
                                                    Active
                                                </button>
                                            </form>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-2'>No users found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
