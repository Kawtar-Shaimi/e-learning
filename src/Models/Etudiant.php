<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";
include_once __DIR__."/User.php";

class Etudiant extends User{
    public function __construct($name = "",$email = "",$role = "",$pass = "",$confirmPass = ""){
        User::__construct($name,$email,$role,$pass,$confirmPass);
        $this->setName($name);
        $this->setEmail($email);
        $this->setRole($role);
        $this->setPass($pass);
        $this->setConfirmPass($confirmPass);
    }

    public function inscriptionsToCours($id_etudiant, $id_cour){
        try{
            $sql = "INSERT INTO inscriptions (id_cour, id_etudiant) VALUES (?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ii", $id_cour, $id_etudiant);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Inscriptions Success!";

            header("Location: /e-learning/pages/Cours/cour.php?id=$id_cour");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function checkInscription($id_etudiant, $id_cour){
        try{
            $sql = "SELECT * FROM inscriptions WHERE id_cour = ? AND id_etudiant = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ii", $id_cour, $id_etudiant);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getEtudiantCoursCount($id_etudiant){
        try{
            $sql = "SELECT COUNT(*)FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user
            INNER JOIN inscriptions
            ON cours.id_cour = inscriptions.id_cour
            WHERE inscriptions.id_etudiant = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_etudiant);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_column();
            return $count;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getEtudiantCours($id_etudiant, $limit, $offset){
        try{
            $sql = "SELECT * FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user
            INNER JOIN inscriptions
            ON cours.id_cour = inscriptions.id_cour
            WHERE inscriptions.id_etudiant = ?
            LIMIT ? OFFSET ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("iii", $id_etudiant, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}