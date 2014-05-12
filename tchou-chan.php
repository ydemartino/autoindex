<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
if (isset($_FILES['torrent'])) {
 $uploaddir  = '/home/yoann/';
 $uploadfile = $uploaddir . basename($_FILES['torrent']['name']);
 if (move_uploaded_file($_FILES['torrent']['tmp_name'], $uploadfile)) {
  header('location: ' . $_SERVER['REQUEST_URI']); exit;
 }
 echo 'ça chie...';
}
?><!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  padding: 0 100px 100px;
  background-color: #ccddee !important;
 }
 h1#header {
  text-align: center;
  padding: 50px;
  font-size: 62px;
  font-family: "Pacifico";
  text-shadow: 0 2px 2px #3c77b3;
 }
 form {
  position: relative;
  padding-left: 75px;
  padding-right: 75px;
  height: 150px;
  margin-bottom: 30px;
  background-color: #FFF;
  -moz-border-radius: 150px;
  -webkit-border-radius: 150px;
  border-radius: 150px;
 }
 fieldset {
  padding: 10px;
  padding-top: 35px;
  height: 225px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
 }
 label {
  color: #888;
 }
 input#torrent {
  display: inline-block;
 }
 input[type="submit"] {
  margin-left: 25px;
 }
 p {
  margin-top: 20px;
  text-align: center;
 }
 </style>
</head>

<body>
<h1 id="header" class="text-primary">Downloader</h1>

<div class="container">
 <form method="post" enctype="multipart/form-data">
  <fieldset>
   <label for="torrent">Fichier torrent :</label> <input id="torrent" name="torrent" type="file" accept="application/x-bittorrent" required />

   <input type="submit" value="C'est parti !" class="btn btn-primary" />

   <p><a href="downloads.php">Accéder aux téléchargements</a></p>
  </fieldset>
 </form>
</div>

</body>
</html>
