<?php

include_once "../DB/database.php";
include_once "Validator.php";

class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = new DataBase();
    }

    public function login($email, $password) {
        Validator::validateLogin($email, $password);

        $sql = "SELECT idUser, role FROM Utilisateur WHERE email = ? AND password = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            setcookie("user_id", $user['idUser'], time() + 3600, "/");
            setcookie("user_role", $user['role'], time() + 3600, "/");
            return true;
        } else {
            throw new Exception("Invalid login credentials.");
        }
    }

    public function signup($name, $email, $password) {
        Validator::validateSignup($name, $email, $password);

        $sql = "INSERT INTO Utilisateur (nom, email, password, role, activated) VALUES (?, ?, ?, 'Etudiant', 0)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        if (!$stmt->execute()) {
            throw new Exception("Error: Unable to register user.");
        }
    }

    public function logout() {
        setcookie("user_id", "", time() - 3600, "/");
        setcookie("user_role", "", time() - 3600, "/");
    }
}

?>
