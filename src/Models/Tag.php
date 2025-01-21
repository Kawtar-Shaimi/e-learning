<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";

class Tag{
    
    private $db;

    private $tag_name;
    private $id_tag;

    public function __construct($tag_name = "", $id_tag = null){
        $this->tag_name = $tag_name;
        $this->id_tag = $id_tag;
        $this->db = new DataBase();
    }

    public function getTagName() {
        return $this->tag_name;
    }
    
    public function setTagName($tag_name) {
        $this->tag_name = $tag_name;
    }

    public function getIdTag() {
        return $this->id_tag;
    }
    
    public function setIdTag($id_tag) {
        $this->id_tag = $id_tag;
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

    public function getTag(){
        try{

            $sql = "SELECT * FROM tags WHERE id_tag = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $this->id_tag);
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

    public function createTag(){
        if(!Validator::validateTag($this->tag_name)){
            header("Location: /e-learning/pages/Admin/Tags/createTag.php");
            exit;
        }

        try{
            $sql = "INSERT INTO tags (tag_name) VALUES (?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $this->tag_name);
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

    public function updateTag(){
        if(!Validator::validateTag($this->tag_name)){
            header("Location: /e-learning/pages/Admin/Tags/updateTag.php?id=". $this->id_tag);
            exit;
        }

        try{
            $sql = "UPDATE tags SET tag_name = ? WHERE id_tag = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("si", $this->tag_name, $this->id_tag);
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

    public function deleteTag(){
        try{
            $sql = "DELETE FROM tags WHERE id_tag = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $this->id_tag);
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