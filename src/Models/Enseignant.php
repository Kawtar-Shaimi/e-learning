<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";
include_once __DIR__."/User.php";

class Enseignant extends User{
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getEnseignantCoursCount($id_enseignant){
        try{
            
            $sql = "SELECT COUNT(*) FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user 
            WHERE id_enseignant = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_enseignant);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_column();
            return $count;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getEnseignantCours($id_enseignant, $limit, $offset){
        try{
            
            $sql = "SELECT * FROM cours
            INNER JOIN categories
            ON cours.id_category = categories.id_category
            INNER JOIN users
            ON cours.id_enseignant = users.id_user 
            WHERE id_enseignant = ?
            LIMIT ? OFFSET ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("iii", $id_enseignant, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createCour($titre, $description, $contenu, $tags, $id_category, $id_enseignant){

        if(!Validator::validateCreateCour($titre, $description, $contenu, $tags, $id_category)){
            header("Location: /e-learning/pages/Cours/createCour.php");
            exit;
        }

        try{

            $contenuName = $contenu['name'];
            $contenuTmpPath = $contenu['tmp_name'];
            $contenuType = mime_content_type($contenuTmpPath);

            if ($contenuType === 'application/pdf') {
                $uniqueContenuName = uniqid('document-') . '-' . basename($contenuName);
                $contenuPath = "documents/$uniqueContenuName";
                $contenuType = 'document';
            } else {
                $uniqueContenuName = uniqid('video-') . '-' . basename($contenuName);
                $contenuPath = "videos/$uniqueContenuName";
                $contenuType = 'video';
            }

            $sql = "INSERT INTO cours (titre, description, contenu_path, contenu_type, id_category, id_enseignant) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Database error: " . $this->db->conn->error);
            }

            $stmt->bind_param("ssssii", $titre, $description, $contenuPath, $contenuType, $id_category, $id_enseignant);
            $stmt->execute();
            $id_cour = $stmt->insert_id;

            move_uploaded_file($contenuTmpPath, __DIR__ . "/../../contenus/$contenuPath");
            
            foreach($tags as $id_tag){
                $sql = "INSERT INTO cours_tags (id_tag, id_cour) VALUES (?, ?)";
                $stmt = $this->db->conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Database error: " . $this->db->conn->error);
                }

                $stmt->bind_param("ii", $id_tag, $id_cour);
                $stmt->execute();
            }

            $_SESSION['message'] = "Cour Added Successfully";
            $stmt->close();
            $this->db->conn->close();
            
            header("Location: /e-learning/pages/Cours/userCours.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateCour($id_cour, $titre, $description, $contenu, $oldContenuPath, $oldContenuType, $tags, $id_category, $id_enseignant){
        if(!Validator::validateUpdateCour($titre, $description, $id_category)){
            header("Location: /e-learning/pages/Cours/updateCour.php?id=$id_cour");
            exit;
        }

        try{
            if ($contenu['name']) {
                $contenuName = $contenu['name'];
                $contenuTmpPath = $contenu['tmp_name'];
                $contenuType = mime_content_type($contenuTmpPath);

                if ($contenuType === 'application/pdf') {
                    $uniqueContenuName = uniqid('document-') . '-' . basename($contenuName);
                    $contenuPath = "documents/$uniqueContenuName";
                    $contenuType = 'document';
                } else {
                    $uniqueContenuName = uniqid('video-') . '-' . basename($contenuName);
                    $contenuPath = "videos/$uniqueContenuName";
                    $contenuType = 'video';
                }
            }else{
                $contenuPath = $oldContenuPath;
                $contenuType = $oldContenuType;
            }

            $sql = "UPDATE cours SET titre = ?, description = ?, contenu_path = ?, contenu_type = ?, id_category = ? WHERE id_cour = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ssssii", $titre, $description, $contenuPath, $contenuType, $id_category, $id_cour);
            $stmt->execute();

            if($contenuPath != $oldContenuPath){
                $oldContenuPath = __DIR__ . "/../../contenus/$oldContenuPath";
                if (file_exists($oldContenuPath)) {
                    if (unlink($oldContenuPath)) {
                        move_uploaded_file($contenuTmpPath, __DIR__ . "/../../contenus/$contenuPath");
                    }
                }
            }

            if($tags){
                if(count($tags) > 0){
                    $sql = "DELETE FROM cours_tags WHERE id_cour = ?";
                    $stmt = $this->db->conn->prepare($sql);
                    
                    if (!$stmt) {
                        throw new Exception("Database error: " . $this->db->conn->error);
                    }

                    $stmt->bind_param("i", $id_cour);
                    $stmt->execute();
                    foreach($tags as $id_tag){
                        $sql = "INSERT INTO cours_tags (id_tag, id_cour) VALUES (?, ?)";
                        $stmt = $this->db->conn->prepare($sql);
        
                        if (!$stmt) {
                            throw new Exception("Database error: " . $this->db->conn->error);
                        }
        
                        $stmt->bind_param("ii", $id_tag, $id_cour);
                        $stmt->execute();
                    }
                }
            }

            $_SESSION['message'] = "Cour updated successfully!";
            $stmt->close();
            $this->db->conn->close();

            header("Location: /e-learning/pages/Cours/userCours.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getCoursInscriptions($id_enseignant){
        try{
            $sql = "SELECT cours.id_cour, user_name, email, titre, inscriptions.created_at AS inscriptionDate
            FROM users
            INNER JOIN inscriptions
            ON users.id_user = inscriptions.id_etudiant
            INNER JOIN cours
            ON cours.id_cour = inscriptions.id_cour
            WHERE cours.id_enseignant = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_enseignant);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}