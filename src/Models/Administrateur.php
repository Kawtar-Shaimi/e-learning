<?php

include_once "./Utilisateur.php";

class Admin extends Utilisateur {

    // Constructor
    public function __construct($nom = null, $email = null, $password = null, $role = 'Admin', $activate = false) {
        // Call parent constructor to initialize shared attributes
        parent::__construct($nom, $email, $password, $role, $activate);
    }

    // New method specific to Admin: Manage users
    public function manageUsers($conn) {
        try {
            $sql = "SELECT idUser, nom, email, role, activate FROM Utilisateur";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "User ID: " . $row['idUser'] . " - Name: " . $row['nom'] . " - Email: " . $row['email'] . " - Role: " . $row['role'] . " - Activated: " . ($row['activate'] ? 'Yes' : 'No') . "<br>";
                }
            } else {
                echo "No users found.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Admin-specific function to activate a user account
    public function activateUser($conn, $idUser) {
        try {
            $sql = "UPDATE Utilisateur SET activate = 1 WHERE idUser = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idUser);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "User with ID $idUser has been activated.";
            } else {
                echo "Error: Could not activate user.";
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>
