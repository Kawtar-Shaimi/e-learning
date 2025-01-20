<?php 

class Validator{

    public static function validateCsrf(){
        if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            echo "Session expired or invalid request. Please refresh the page.";
            exit();
        }
        unset($_SESSION['csrf_token']);
    }

    public static function validateLogedInUser(){
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_role'])) {
            header("Location: /e-learning/pages/Auth/login.php");
            exit;
        }
    }

    public static function validateLogedOutUser(){
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_role'])){
            header("Location: /e-learning/index.php");
            exit;
        }
    }

    public static function validateAdmin(){
        if($_SESSION['user_role'] != "admin"){
            header("Location: /e-learning/index.php");
            exit;
        }
    }

    public static function validateEnseignant(){
        if($_SESSION['user_role'] != "enseignant"){
            header("Location: /e-learning/index.php");
            exit;
        }
    }

    public static function validateEtudiant(){
        if($_SESSION['user_role'] != "etudiant"){
            header("Location: /e-learning/index.php");
            exit;
        }
    }

    public static function validateLogin($email, $pass){

        $isValid = true;

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }
        
        return $isValid;
    }

    public static function validateSignup($name, $email, $role, $pass, $confirmPass, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                $_SESSION["emailErr"]="This email already existe";
                $isValid = false;
            }
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }

        if(empty($confirmPass)){
            $_SESSION["confirmPassErr"] = "Confirm password is required.";
        }else{
            if($pass != $confirmPass){
                $_SESSION["confirmPassErr"] = "Passwords are Not matching!";
                $isValid = false;
            }
        }

        if (empty($role) || ($role != "enseignant" && $role != "etudiant")) {
            $_SESSION["roleErr"] = "Role is required and must be enseignant or etudiant.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateCreateCour($titre, $description, $contenu, $tags, $id_category){

        $isValid = true;

        if (empty($titre) || strlen($titre) > 100) {
            $_SESSION['titleErr'] = "Title is required and must be 100 characters or less.";
            $isValid = false;
        }
    
        if (empty($description)) {
            $_SESSION['descriptionErr'] = "Description is required.";
            $isValid = false;
        }
    
        if (empty($id_category)) {
            $_SESSION['categoryErr'] = "Category is required.";
            $isValid = false;
        }
    
        if (empty($contenu) || $contenu['error'] != 0) {
            $_SESSION["contenuErr"] = "Contenu is required.";
            $isValid = false;
        }
    
        if(count($tags) <= 0){
            $_SESSION["tagsErr"] = "Tags are required.";
        }

        return $isValid;
    }

    public static function validateUpdateCour($titre, $description, $id_category){

        $isValid = true;

        if (empty($titre) || strlen($titre) > 100) {
            $_SESSION['titleErr'] = "Title is required and must be 100 characters or less.";
            $isValid = false;
        }
    
        if (empty($description)) {
            $_SESSION['descriptionErr'] = "Description is required.";
            $isValid = false;
        }

        if (empty($id_category)) {
            $_SESSION['categoryErr'] = "Category is required.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateCategory($category_name){

        $isValid = true;

        if (empty($category_name) || strlen($category_name) > 50) {
            $_SESSION['categoryNameErr'] = "Category name is required and must be 50 characters or less.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateTag($tag_name){

        $isValid = true;

        if (empty($tag_name) || strlen($tag_name) > 50) {
            $_SESSION['tagNameErr'] = "Tag name is required and must be 50 characters or less.";
            $isValid = false;
        }

        return $isValid;
    }

}