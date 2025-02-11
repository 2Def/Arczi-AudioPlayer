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
  <title>Słuchaj - Audiobook</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <?php require_once('modules/nav.php'); ?>
  <div class="container">
    <!-- Sekcja z informacjami o książce -->
    <div class="book-card" style="flex-direction: column; align-items: center;">
      <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki" style="width: 200px; height: 300px;">
      <h2>Tytuł Książki 1</h2>
      <p>Krótki opis książki, który przybliża treść i zachęca do słuchania.</p>
    </div>
    
    <!-- Lista rozdziałów (obszar przewijany poziomo) -->
    <div class="chapter-list">
    <div class="chapter-item active" data-audio="audio/chapter1.mp3">
        <div>Rozdział 1</div>
        <div class="chapter-name">Wprowadzenie</div>
        <small>50% przesłuchane</small>
    </div>
    <div class="chapter-item" data-audio="audio/chapter2.mp3">
        <div>Rozdział 2</div>
        <div class="chapter-name">Przygoda</div>
        <small>0% przesłuchane</small>
    </div>
    <div class="chapter-item" data-audio="audio/chapter3.mp3">
        <div>Rozdział 3</div>
        <div class="chapter-name">Kulminacja</div>
        <small>0% przesłuchane</small>
    </div>
    <div class="chapter-item" data-audio="audio/chapter4.mp3">
        <div>Rozdział 4</div>
        <div class="chapter-name">Zakończenie</div>
        <small>0% przesłuchane</small>
    </div>
    </div>
    
    <div class="player">
        <h3>Rozdział 17</h3>
        <audio id="audio" src="/dashboard/uploads/chapters/chapter_678c18e258e804.18196159.mp3"></audio>
        <div class="time">
            <span id="currentTime">0:00</span> / <span id="duration">0:00</span>
        </div>
        <input type="range" id="progress" value="0" min="0" step="1">
        <div class="controls">
            <button onclick="rewind()">⏪</button>
            <button  id="playPauseButton"  onclick="togglePlayPause()">▶️</button>
            <button onclick="forward()">⏩</button>
        </div>
    </div>
  </div>
  <script src="assets/script.js"></script>
</body>
</html>
