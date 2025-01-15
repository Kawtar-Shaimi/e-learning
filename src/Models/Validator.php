<?php

class Validator {
    // Method to validate CSRF tokens
    public static function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }
    }

    // Method to validate login inputs
    public static function validateLogin($email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters.");
        }
    }

    // Method to validate signup inputs
    public static function validateSignup($name, $email, $password) {
        if (empty($name)) {
            throw new Exception("Name is required.");
        }
        self::validateLogin($email, $password);
    }

    // Method to validate other general inputs
    public static function validateGeneralInput($input, $fieldName = "Input") {
        if (empty($input)) {
            throw new Exception("$fieldName cannot be empty.");
        }
    }
}

?>
