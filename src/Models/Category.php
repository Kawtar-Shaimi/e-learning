<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";

class Category{
    
    private $db;

    private $category_name;
    private $id_category;

    public function __construct($category_name = "", $id_category = null){
        $this->category_name = $category_name;
        $this->id_category = $id_category;
        $this->db = new DataBase();
    }

    public function getCategoryName() {
        return $this->category_name;
    }
    
    public function setCategoryName($category_name) {
        $this->category_name = $category_name;
    }

    public function getIdCategory() {
        return $this->id_category;
    }
    
    public function setIdCategory($id_category) {
        $this->id_category = $id_category;
    }


    public function getCategories(){
        try{

            $sql = "SELECT * FROM categories";
            $result = $this->db->conn->query($sql);
            return $result;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getCategory(){
        try{

            $sql = "SELECT * FROM categories WHERE id_category = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $this->id_category);
            $stmt->execute();
            $result = $stmt->get_result();
            $category = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $category;

        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createCategory(){
        if(!Validator::validateCategory($this->category_name)){
            header("Location: /e-learning/pages/Admin/Categories/createCategory.php");
            exit;
        }

        try{
            $sql = "INSERT INTO categories (category_name) VALUES (?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $this->category_name);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Category added successfully!";

            header("Location: /e-learning/pages/Admin/Categories/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateCategory(){
        if(!Validator::validateCategory($this->category_name)){
            header("Location: /e-learning/pages/Admin/Categories/updateCategory.php?id=". $this->id_category);
            exit;
        }

        try{
            $sql = "UPDATE categories SET category_name = ? WHERE id_category = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("si", $this->category_name, $this->id_category);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Category updated successfully!";

            header("Location: /e-learning/pages/Admin/Categories/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteCategory(){
        try{
            $sql = "DELETE FROM categories WHERE id_category = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $this->id_category);
            $stmt->execute();
            $stmt->close();
            $this->db->conn->close();

            $_SESSION['message'] = "Category deleted successfully!";

            header("Location: /e-learning/pages/Admin/Categories/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}