<?php
session_start();

$app_id = ''; //Facebook App ID
$app_secret = ''; //Facebook App Secret
$redirect_uri = 'http://localhost/fblogin/fb-login.php'; //visszairanyitasi URL

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    //hozzafeeresi token lekerese
    $token_url = "https://graph.facebook.com/v16.0/oauth/access_token?"
    . "client_id={$app_id}&redirect_uri=" . urlencode($redirect_uri)
    . "&client_secret={$app_secret}&code={$code}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //fejlesztasi calra
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $params = json_decode($response, true);

        if (isset($params['access_token'])) {
            $access_token = $params['access_token'];

            //felhasznaloi adatok lekerese az access tokennel
            $graph_url = "https://graph.facebook.com/me?fields=id,name,email,picture.type(large)&access_token={$access_token}";
            $user_info = json_decode(file_get_contents($graph_url), true);

            //felhasznalo adatai mentese session-ben
            $_SESSION['user_name'] = $user_info['name'] ?? 'N/A';
            $_SESSION['user_email'] = $user_info['email'] ?? 'N/A';
            $_SESSION['user_picture'] = $user_info['picture']['data']['url'] ?? 'N/A';
            $_SESSION['fb_access_token'] = $access_token;

            //tovabbiranyitas az index.php oldalra
            header("Location: index.php");
            exit();
        } else {
            echo "Nem sikerült hozzáférési tokent szerezni. Hiba: " . htmlspecialchars($response);
        }
    } else {
        echo "Hiba történt a hozzáférési token lekérésekor. HTTP státusz kód: $http_code<br>Válasz: " . htmlspecialchars($response);
    }
} else {
    //nincs Facebook bejelentkezesi oldalara
    $login_url = "https://www.facebook.com/v16.0/dialog/oauth?client_id={$app_id}&redirect_uri=" . urlencode($redirect_uri) . "&scope=email";
    header("Location: $login_url");
    exit();
}
?>
