<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";

class User{
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function signup($name, $email, $role, $pass, $confirmPass){

        if(!Validator::validateSignup($name, $email, $role, $pass, $confirmPass, $this->db->conn)){
            header("Location: /e-learning/pages/Auth/signup.php");
            exit;
        }

        try{
            $hashPass = password_hash($pass,PASSWORD_BCRYPT);

            $status = $role === 'etudiant' ? "active" : "non_valide";

            $sql = "INSERT INTO users (user_name, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $hashPass, $role, $status);
            $stmt->execute();
            $user_id = $stmt->insert_id;
            $stmt->close();
            $this->db->conn->close();

            if ($role === 'etudiant') {
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_role"] = "user";

                header("Location: /e-learning/index.php");
                exit;
            }

            header("Location: /e-learning/pages/Auth/login.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function login($email, $pass){

        if(!Validator::validateLogin($email, $pass)){
            header("Location: /e-learning/pages/Auth/login.php");
            exit;
        }

        try{
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($pass, $user["password"])) {
                    
                    if ($user['role'] === "enseignant") {
                        if ($user['status'] === "valide") {
                            $_SESSION["user_id"] = $user["id_user"];
                            $_SESSION["user_role"] = $user["role"];
                        
                            $stmt->close();
                            $this->db->conn->close();
                            header("Location: /e-learning/index.php");
                            exit;
                        } else {
                            $_SESSION["loginErr"] = "Your account is not valide!";
                            $stmt->close();
                            $this->db->conn->close();
                            header("Location: /e-learning/pages/Auth/login.php");
                            exit;
                        }
                    } else if ($user['role'] === "etudiant"){
                        if ($user['status'] === "active") {
                            $_SESSION["user_id"] = $user["id_user"];
                            $_SESSION["user_role"] = $user["role"];
                        
                            $stmt->close();
                            $this->db->conn->close();
                            header("Location: /e-learning/index.php");
                            exit;
                        } else {
                            $_SESSION["loginErr"] = "Your account is suspensed!";
                            $stmt->close();
                            $this->db->conn->close();
                            header("Location: /e-learning/pages/Auth/login.php");
                            exit;
                        }
                    }else{
                        $_SESSION["user_id"] = $user["id_user"];
                        $_SESSION["user_role"] = $user["role"];
                    
                        $stmt->close();
                        $this->db->conn->close();
                        header("Location: /e-learning/pages/Admin/Users/index.php");
                        exit;
                    }
                }else{
                    $_SESSION["loginErr"] = "Email or Password is incorrect!";
                    $stmt->close();
                    $this->db->conn->close();
                    header("Location: /e-learning/pages/Auth/login.php");
                    exit;
                }
            }else{
                $_SESSION["loginErr"] = "Email or Password is incorrect!";
                $stmt->close();
                $this->db->conn->close();
                header("Location: /e-learning/pages/Auth/login.php");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function logout(){
        try {
            unset($_SESSION["user_id"], $_SESSION["user_role"]);
            header("Location: /e-learning/pages/Auth/login.php");
            exit;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

}