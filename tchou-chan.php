<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

function processTorrent($file) {
  require('classes/Lightbenc.php');
  $t = Lightbenc::bdecode($file);
  return (is_array($t['info']['files']) ? '/' : '#') . $t['info']['name'];
}

$msg = $flash = '';
$uploaddir  = '/home/yoann/';
$t411prefix = 'http://www.t411.me/torrents/';
if (isset($_COOKIE['flash'])) {
    $flash = $_COOKIE['flash'];
    unset($_COOKIE['flash']);
    setcookie('flash', '', time() - 3600);
}
if (isset($_FILES['torrent'])) {
 $uploadfile = $uploaddir . basename($_FILES['torrent']['name']);
 if (move_uploaded_file($_FILES['torrent']['tmp_name'], $uploadfile)) {
  setcookie('flash', 'C\'est bon pour le torrent ;)', time() + 5);
  header('location: ' . $_SERVER['REQUEST_URI']); exit;
 }
 $msg = 'L\'upload du fichier torrent a foiré !!';
}
if (isset($_POST['url'])) {
  if ($_POST['url'][4] == 's') {
      $_POST['url'] = substr_replace($_POST['url'], '', 4, 1);
  }
  if (false !== strstr($_POST['url'], $t411prefix)) {
    $secret = 'wiLlRocktHaTBOAt';
    $opts = array('http' => array('method' => 'GET', 'header' => 'Cookie: uid=?; pass=?; authKey=?; authApi=?' . "\r\n" . 'User-Agent: Lynx/2.8.8dev.12 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/2.12.18'));
    $context = stream_context_create($opts);
    $res = file_get_contents($_POST['url'], false, $context);
    if (false !== $res && $res[0] == '<') {
		$tabernak = preg_match('!\(VFQ/French\)!', $res);
		if (1 === $tabernak) {
			$msg = '<p text-align="center">MORT AUX TABERNAK<br/><img src="//d1yk11tqvlcywn.cloudfront.net/keep-calm-and-fuck-canada.png" alt="KEEP CALM AND FUCK CANADA"/></p>';
		} else {
			$res = preg_match('!download/\?id=([0-9]+)!', $res, $output);
			if (false !== $res) {
				$res = file_get_contents($t411prefix . $output[0], false, $context);
			}
		}
    }
    if (false !== $res && strpos($res, 'd8:announce') === 0) {
        file_put_contents($uploaddir . str_shuffle($secret) . '.torrent', $res);
        setcookie('flash', 'C\'est bon pour le torrent ;) <a href="downloads.php?dir='.processTorrent($res).'">Ici</a> pour le récup !', time() + 5);
        header('location: ' . $_SERVER['REQUEST_URI']); exit;
    }
	if ('' == $msg) $msg = 'Y a un truc qui va pas avec le lien que t\'as filé...';
  } else {
    $msg = 'Le lien commence pas par ce qu\'il faut...';
  }
}
?><!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
 <link href="//d1yk11tqvlcywn.cloudfront.net/styles/main.min.css" rel="stylesheet">
</head>

<body>
<h1 id="header" class="text-primary">Downloader</h1>

<div class="container">
<?php
if ($flash != '') { ?>
 <div class="alert alert-success"><?= $flash ?></div>
<?php }
if ($msg != '') { ?>
 <div class="alert alert-warning"><?= $msg ?></div>
<?php } ?>
 <form method="post">
  <fieldset>
   <label for="url">Lien du torrent :</label> <input id="url" name="url" type="url" required />

   <input type="submit" value="C'est parti !" class="btn btn-primary" />

   <p><a href="downloads.php">Accéder aux téléchargements</a></p>
  </fieldset>
 </form>
</div>

</body>
</html>
