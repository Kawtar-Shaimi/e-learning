<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";

class Cour{
    
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getCours(){
        try{
            
            $sql = "SELECT *, cours.created_at AS dateCreation , cours.updated_at AS dateModification 
            FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user";
            $result = $this->db->conn->query($sql);
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getPaginatedCours($limit, $offset){
        try{
            
            $sql = "SELECT *, cours.created_at AS dateCreation , cours.updated_at AS dateModification 
            FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user
            LIMIT ? OFFSET ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getCour($id_cour){
        try{
            
            $sql = "SELECT * FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user WHERE id_cour = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_cour);
            $stmt->execute();
            $result = $stmt->get_result();
            $article = $result->fetch_assoc();
            return $article;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteCour($id_cour, $role){
        try{
            $sql = "SELECT contenu_path FROM cours WHERE id_cour = ?";
            $stmt = $this->db->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Database error: " . $this->db->conn->error);
            }

            $stmt->bind_param("i", $id_cour);
            $stmt->execute();
            $result = $stmt->get_result();
            $contenuPath = __DIR__ . "/../../contenus/" . $result->fetch_column();

            if (file_exists($contenuPath)) {
                if (unlink($contenuPath)) {
                    $sql = "DELETE FROM cours WHERE id_cour = ?";
                    $stmt = $this->db->conn->prepare($sql);
                    
                    if (!$stmt) {
                        throw new Exception("Database error: " . $this->db->conn->error);
                    }

                    $stmt->bind_param("i", $id_cour);
                    $stmt->execute();
                    $_SESSION['message'] = "Cour Deleted Successfully";
                    $stmt->close();
                    $this->db->conn->close();

                    if($role === "admin"){
                        header("Location: /e-learning/pages/Admin/Cours/index.php");
                        exit;
                    }else{
                        header("Location: /e-learning/pages/Cours/userCours.php");
                        exit;
                    }
                }
            }
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getCourTags($id_cour){
        try{
            $sql = "SELECT tag_name FROM tags
                    INNER JOIN cours_tags
                    ON tags.id_tag = cours_tags.id_tag
                    where cours_tags.id_cour = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_cour);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getSearchCours($query, $limit, $offset){
        try{
            $sql = "SELECT * FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user
            WHERE cours.titre LIKE ? OR cours.description Like ?
            LIMIT ? OFFSET ?";
            $stmt = $this->db->conn->prepare($sql);
            $searchQuery = "%{$query}%";
            $stmt->bind_param("ssii", $searchQuery, $searchQuery, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getTotalCours(){
        try{
            
            $sql = "SELECT COUNT(*) AS total FROM cours";
            $result = $this->db->conn->query($sql);
            $total = $result->fetch_column();
            return $total;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

}