<?php

    require_once('functions.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = connectToDb();

    $statement = $db->prepare("SELECT * FROM users WHERE username = ?");
    $statement->bind_param('s', $username);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->fetch_assoc();


    if ( ! $user) {
        header('Location: index.php');
        exit();
    }

    $hashedPassword = $user['password'];

    if ( ! password_verify($password, $hashedPassword)) { 
        header('Location: index.php');
        exit();
    }

    $_SESSION['loggedIn'] = TRUE;
    $_SESSION['userId'] = $user['id'];
    header('Location: members.php');

    ?>


