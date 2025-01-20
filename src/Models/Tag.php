<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";

class Tag{
    
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getTags(){
        try{

            $sql = "SELECT * FROM tags";
            $result = $this->db->conn->query($sql);
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getTag($id_tag){
        try{

            $sql = "SELECT * FROM tags WHERE id_tag = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_tag);
            $stmt->execute();
            $result = $stmt->get_result();
            $tag = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $tag;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createTag($tag_name){
        if(!Validator::validateTag($tag_name)){
            header("Location: /e-learning/pages/Admin/Tags/createTag.php");
            exit;
        }

        try{
            $sql = "INSERT INTO tags (tag_name) VALUES (?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $tag_name);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Tag added successfully!";

            header("Location: /e-learning/pages/Admin/Tags/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateTag($tag_name, $id_tag){
        if(!Validator::validateTag($tag_name)){
            header("Location: /e-learning/pages/Admin/Tags/updateTag.php?id=$id_tag");
            exit;
        }

        try{
            $sql = "UPDATE tags SET tag_name = ? WHERE id_tag = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("si", $tag_name, $id_tag);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Tag updated successfully!";

            header("Location: /e-learning/pages/Admin/Tags/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteTag($id_tag){
        try{
            $sql = "DELETE FROM tags WHERE id_tag = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id_tag);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Tag deleted successfully!";

            header("Location: /e-learning/pages/Admin/Tags/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}