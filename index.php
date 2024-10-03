<?php
session_start();

//ellenorizzük hogy a felhasznalo be van jelentkezve
if (isset($_SESSION['fb_access_token'])) {
    //felhasználói adatok megjelenítése
    echo "<h1>Üdvözöllek, " . htmlspecialchars($_SESSION['user_name']) . "!</h1>";
    echo "<p>Email: " . htmlspecialchars($_SESSION['user_email']) . "</p>";
    echo "<img src='" . htmlspecialchars($_SESSION['user_picture']) . "' alt='Profilkép'><br><br>";
    echo '<a href="logout.php"><button>Kijelentkezés</button></a>';
} else {
    //nincs bejelentkezve vissza a bejelentkezesi oldalra
    header("Location: login.html");
    exit();
}
?>
