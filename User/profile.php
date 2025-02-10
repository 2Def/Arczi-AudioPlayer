<?php

define('ACCESS_ALLOWED', true);
define('MODULES', 'modules/');
session_start();

require(MODULES . 'checkBlock.php');

?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil - Audiobooki</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php require_once('modules/nav.php'); ?>
  <div class="container">
    <h1>Profil użytkownika</h1>
    <table class="profile-table">
      <tr>
        <th>Nazwa użytkownika</th>
        <td>Jan Kowalski</td>
      </tr>
      <tr>
        <th>Ranga</th>
        <td>Premium</td>
      </tr>
      <tr>
        <th>Ilość przeczytanych książek</th>
        <td>12</td>
      </tr>
      <tr>
        <th>Minuty przeczytanych książek</th>
        <td>720</td>
      </tr>
    </table>
    
    <h2>Przeczytane książki</h2>
    <div class="read-books">
      <div class="read-book-item">
        <span>Tytuł Książki 1</span>
      </div>
      <div class="read-book-item">
        <span>Tytuł Książki 2</span>
      </div>
      <div class="read-book-item">
        <span>Tytuł Książki 3</span>
      </div>
      <div class="read-book-item">
        <span>Tytuł Książki 4</span>
      </div>
      <!-- Dodaj kolejne elementy według potrzeb -->
    </div>
  </div>
</body>
</html>
