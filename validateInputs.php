<?php
function validateRegistrationInput($username, $email, $password, $confirmPassword, $telephone, $address) {
        $errors = [];

        // Validate username
        if (empty($username)) {
            $errors['username'] = "Username is required.";
        } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            $errors['username'] = "Username can only contain letters, numbers, and underscores.";
        } elseif (!validateUserName($username)) {
            $errors['username'] = "Username already taken. Please choose a different username.";
        } 

        // Validate email
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format. Please enter a valid email address.";
        }

        // Validate password
        if (empty($password)) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters long.";
        }

        // Confirm password
        if ($password !== $confirmPassword) {
            $errors['confirmPassword'] = "Passwords do not match.";
        }

// Example usage
    $name = "John Doe";
    $username = generateUsername($name);
    echo "Username for $name: $username";


    // Validate telephone (optional)
        // Add validation rules based on your requirements

        // Validate address
        if (empty($address)) {
            $errors['address'] = "Address is required.";
        }

        return $errors;
    }

    function generateUsername($name): array|string|null
    {
        // Convert name to lowercase
        $name = strtolower($name);

        // Remove spaces and special characters
        $username = preg_replace('/^[a-zA-Z0-9_]+$/', '', $name);

        // Ensure the username is not empty
        if(empty($username)) {
            // If the username is empty, generate a default one
            $username = 'user' . uniqid();
        }

        return $username;
    }
    
?>