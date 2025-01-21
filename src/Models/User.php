<?php 

include_once __DIR__."/../DB/DataBase.php";
include_once __DIR__."/Validator.php";

class User{
    protected $db;
    protected $name;
    protected $email;
    protected $role;
    protected $pass;
    protected $confirmPass;

    public function __construct($name = "",$email = "",$role = "",$pass = "",$confirmPass = ""){
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->pass = $pass;
        $this->confirmPass = $confirmPass;
        $this->db = new DataBase();
    }

    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getRole() {
        return $this->role;
    }
    public function setRole($role) {
        $this->role = $role;
    }
    
    public function getPass() {
        return $this->pass;
    }
    public function setPass($pass) {
        $this->pass = $pass;
    }
    
    public function getConfirmPass() {
        return $this->confirmPass;
    }
    public function setConfirmPass($confirmPass) {
        $this->confirmPass = $confirmPass;
    }

    public function signup(){

        if(!Validator::validateSignup($this->name, $this->email, $this->role, $this->pass, $this->confirmPass, $this->db->conn)){
            header("Location: /e-learning/pages/Auth/signup.php");
            exit;
        }

        try{
            $hashPass = password_hash($this->pass,PASSWORD_BCRYPT);

            $status = $this->role === 'etudiant' ? "active" : "non_valide";

            $sql = "INSERT INTO users (user_name, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssss", $this->name, $this->email, $hashPass, $this->role, $status);
            $stmt->execute();
            $user_id = $stmt->insert_id; 
            $stmt->close();
            $this->db->conn->close();

            if ($this->role === 'etudiant') {
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

    public function login(){

        if(!Validator::validateLogin($this->email, $this->pass)){
            header("Location: /e-learning/pages/Auth/login.php");
            exit;
        }

        try{
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $this->email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($this->pass, $user["password"])) {
                    
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