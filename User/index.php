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
  <title>Strona Główna - Audiobooki</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php require_once('modules/nav.php'); ?>
  <div class="container">
    <div class="header-container">
        <h1>Audiobooki</h1>
    </div>
    <div class="book-container">
    <!-- Przykładowa karta książki z informacją o postępie -->
      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 1</h3>
          <p>Krótki opis książki, który zachęci do słuchania.</p>
          <p class="progress">Aktualnie czytane - 45% przesłuchane</p>
        </div>
        <button onclick="window.location.href='listen.php'">Słuchaj</button>
      </div>
      
      <!-- Kolejna przykładowa karta książki -->
      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 2</h3>
          <p>Opis innej książki, zachęcający do odsłuchu. Opis innej książki, zachęcający do odsłuchu.Opis innej książki, zachęcający do odsłuchu.Opis innej książki, zachęcający do odsłuchu.</p>
        </div>
        <button onclick="window.location.href='listen.php'">Słuchaj</button>
      </div>

      <!-- Kolejna przykładowa karta książki -->
      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 2</h3>
          <p>Opis innej książki, zachęcający do odsłuchu.</p>
        </div>
        <button onclick="window.location.href='listen.php'">Słuchaj</button>
      </div>

      <!-- Kolejna przykładowa karta książki -->
      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 2</h3>
          <p>Opis innej książki, zachęcający do odsłuchu.</p>
        </div>
        <button onclick="window.location.href='listen.php'">Słuchaj</button>
      </div>

      <!-- Kolejna przykładowa karta książki -->
      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 2</h3>
          <p>Opis innej książki, zachęcający do odsłuchu.</p>
        </div>
        <button onclick="window.location.href='listen.php'">Słuchaj</button>
      </div>

      <!-- Kolejna przykładowa karta książki -->
      <div class="book-card">
        <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
        <div class="book-details">
          <h3>Tytuł Książki 2</h3>
          <p>Opis innej książki, zachęcający do odsłuchu.</p>
        </div>
        <button onclick="window.location.href='listen.php'">Słuchaj</button>
      </div>

    </div>
  </div>
</body>
</html>
