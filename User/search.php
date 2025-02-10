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
  <title>Wyszukiwanie - Audiobooki</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php require_once('modules/nav.php'); ?>
  <div class="container">
    
    <div class="header-container">
      <h1 class="no-wrap">Wyszukiwanie</h1>
    </div>

    <div class="search-container">
      <input type="search" id="searchInput" placeholder="Szukaj książek...">
      <button onclick="performSearch()">Szukaj</button>
    </div>
    
    <div class="book-container">
    <!-- Przykładowy wynik wyszukiwania -->

      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 3</h3>
          <p>Krótki opis książki.</p>
          <button onclick="window.location.href='listen.php'">Słuchaj</button>
        </div>
      </div>

      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 3</h3>
          <p>Opis innej książki, zachęcający do odsłuchu.Opis innej książki</p>
          <button onclick="window.location.href='listen.php'">Słuchaj</button>
        </div>
      </div>

    </div>
  </div>
  <script>
    function performSearch() {
      // Przykładowa funkcja – tutaj możesz dodać logikę wyszukiwania
      alert('Funkcja wyszukiwania nie jest jeszcze zaimplementowana.');
    }
  </script>
</body>
</html>
