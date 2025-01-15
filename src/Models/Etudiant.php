<?php

include_once "DataBase.php";
include_once "Validator.php";

class Etudiant extends Utilisateur {
    private $db;

    public function __construct() {
        $this->db = new DataBase();
    }

    public function consulterCatalogue() {
        $sql = "SELECT idCours, titre, description, categorie FROM Cours";
        $result = $this->db->conn->query($sql);
        if (!$result) {
            throw new Exception("Error fetching catalogue: " . $this->db->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function rechercherCours($searchTerm) {
        Validator::validateGeneralInput($searchTerm, "Search Term");

        $sql = "SELECT idCours, titre, description, categorie FROM Cours WHERE titre LIKE ? OR description LIKE ?";
        $stmt = $this->db->conn->prepare($sql);
        $likeTerm = "%" . $searchTerm . "%";
        $stmt->bind_param("ss", $likeTerm, $likeTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error searching for course: " . $this->db->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function lireContenuCours($idCours) {
        Validator::validateGeneralInput($idCours, "Course ID");

        if (!isset($_COOKIE["user_id"])) {
            header("Location: /e-learning/register.php");
            exit;
        }

        $sql = "SELECT contenu FROM Cours WHERE idCours = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("i", $idCours);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error reading course content: " . $this->db->conn->error);
        }
        $content = $result->fetch_assoc();
        return $content['contenu'];
    }

    public function sInscrireCours($idCours) {
        Validator::validateGeneralInput($idCours, "Course ID");

        if (!isset($_COOKIE["user_id"])) {
            throw new Exception("You must be logged in to enroll in a course.");
        }

        $userId = $_COOKIE["user_id"];
        $sql = "INSERT INTO Etudiant_Cours (idEtudiant, idCours) VALUES (?, ?)";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $idCours);
        if (!$stmt->execute()) {
            throw new Exception("Error enrolling in course: " . $this->db->conn->error);
        }
    }

    public function consulterMesCours() {
        if (!isset($_COOKIE["user_id"])) {
            throw new Exception("You must be logged in to view your courses.");
        }

        $userId = $_COOKIE["user_id"];
        $sql = "SELECT Cours.* FROM Cours INNER JOIN Etudiant_Cours ON Cours.idCours = Etudiant_Cours.idCours WHERE Etudiant_Cours.idEtudiant = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error fetching your courses: " . $this->db->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>
