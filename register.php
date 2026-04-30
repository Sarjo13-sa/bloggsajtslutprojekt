<?php

session_start();

require_once(__DIR__ . '/functions.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $error = "Alla fält måste fyllas i.";
    } elseif ($password !== $confirmPassword) {
        $error = "Lösenorden matchar inte.";
    } else {

        $db = connectToDb();

        // Kontrollera om användarnamnet redan finns
        $statement = $db->prepare("SELECT id FROM users WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();

        $result = $statement->get_result();
        $existingUser = $result->fetch_assoc();

        if ($existingUser) {
            $error = "Användarnamnet är redan upptaget.";
        } else {

            // Hasha lösenordet innan det sparas
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $statement = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $statement->bind_param("ss", $username, $hashedPassword);

            if ($statement->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Något gick fel när användaren skulle skapas.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Registrera konto</title>
</head>
<body>

    <h1>Registrera konto</h1>

    <?php if (!empty($error)) : ?>
        <p style="color: red;">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>

    <form action="register.php" method="POST">

        <label for="username">Användarnamn</label><br>
        <input type="text" name="username" id="username"><br><br>

        <label for="password">Lösenord</label><br>
        <input type="password" name="password" id="password"><br><br>

        <label for="confirm_password">Bekräfta lösenord</label><br>
        <input type="password" name="confirm_password" id="confirm_password"><br><br>

        <button type="submit">Skapa konto</button>

    </form>

    <p>
        Har du redan ett konto?
        <a href="index.php">Logga in här</a>
    </p>

</body>
</html>