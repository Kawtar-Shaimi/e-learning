<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";
include_once __DIR__."/User.php";

class Admin extends User{

    public function __construct($name = "",$email = "",$role = "",$pass = "",$confirmPass = ""){
        User::__construct($name,$email,$role,$pass,$confirmPass);
        $this->setName($name);
        $this->setEmail($email);
        $this->setRole($role);
        $this->setPass($pass);
        $this->setConfirmPass($confirmPass);
    }

    public function getUsers(){
        try{
            $sql = "SELECT * FROM users WHERE status = 'valide' OR status = 'active'";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getUsersByStatus($status){
        try{
            $sql = "SELECT * FROM users WHERE status = ?;";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $status);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getUser($id_user){
        try{
            $sql = "SELECT * FROM users WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $user;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteUser($id_user){
        try{
            $sql = "DELETE FROM users WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_user);
            if($stmt->execute()){
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "User deleted successfully";
                header("Location: /e-learning/pages/Admin/Users/index.php");
                exit;
            }else{
                $_SESSION["message"] = "Error deleting user";
                header("Location: /e-learning/pages/Admin/Users/index.php");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
    
    public function changeUserStatus($status, $id_user){
        try{
            $sql = "UPDATE users SET status = ? WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("si", $status, $id_user);
            if($stmt->execute()){
                $stmt->close();
                $this->db->conn->close();
                if ($status === "suspense") {
                    $_SESSION["message"] = "User suspensed successfully";
                } elseif ($status === "valide") {
                    $_SESSION["message"] = "User validated successfully";
                } elseif ($status === "non_valide") {
                    $_SESSION["message"] = "User invalidated successfully";
                } else {
                    $_SESSION["message"] = "User activated successfully";
                }
                header("Location: /e-learning/pages/Admin/Users/index.php");
                exit;
            }else{
                if ($status === "suspense") {
                    $_SESSION["message"] = "Error suspending user";
                } elseif ($status === "valide") {
                    $_SESSION["message"] = "Error validating user";
                } elseif ($status === "non_valide") {
                    $_SESSION["message"] = "Error invalidating user";
                } else {
                    $_SESSION["message"] = "Error activating user";
                }
                header("Location: /e-learning/pages/Admin/Users/index.php");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getRolesCount(){
        try{
            $sql = "SELECT role ,COUNT(*) AS roleCount 
            FROM users 
            GROUP BY role";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getCourCountByCat(){
        try{
            $sql = "SELECT category_name, COUNT(cours.id_category) AS categoryCount
            FROM categories
            LEFT JOIN cours
            ON cours.id_category = categories.id_category
            GROUP BY categories.id_category";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getTopEnseignants(){
        try{
            $sql = "SELECT user_name, COUNT(inscriptions.id_cour) AS inscriptionsCount
            FROM users
            INNER JOIN inscriptions
            ON users.id_user = inscriptions.id_etudiant
            INNER JOIN cours
            ON cours.id_cour = inscriptions.id_cour
            GROUP BY users.user_name, cours.id_enseignant
            ORDER BY inscriptionsCount DESC
            LIMIT 3";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getTopCour(){
        try{
            $sql = "SELECT cours.id_cour, titre, COUNT(inscriptions.id_etudiant) AS etudiantsCount
            FROM cours
            INNER JOIN inscriptions
            ON cours.id_cour = inscriptions.id_cour
            GROUP BY cours.id_cour, titre
            ORDER BY etudiantsCount DESC
            LIMIT 1";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

}


