<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Strona Główna - Audiobooki</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <nav>
    <a href="index.php">Strona Główna</a>
    <a href="search.php">Szukaj</a>
    <a href="profile.php">Profil</a>
    <a href="login.php">Logowanie</a>
  </nav>
  <div class="container">
    <div class="header-container">
        <h1>Audiobooki</h1>
    </div>
    <!-- Przykładowa karta książki z informacją o postępie -->
    <div class="book-card">
      <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
      <div class="book-details">
        <h3>Tytuł Książki 1</h3>
        <p>Krótki opis książki, który zachęci do słuchania.</p>
        <p class="progress">Aktualnie czytane - 45% przesłuchane</p>
        <button onclick="window.location.href='listen.html'">Słuchaj</button>
      </div>
    </div>
    
    <!-- Kolejna przykładowa karta książki -->
    <div class="book-card">
      <img src="../dashboard/uploads/covers/cover_678a8c382a10a1.04483360.jpg" alt="Okładka książki">
      <div class="book-details">
        <h3>Tytuł Książki 2</h3>
        <p>Opis innej książki, zachęcający do odsłuchu.</p>
        <button onclick="window.location.href='listen.html'">Słuchaj</button>
      </div>
    </div>
  </div>
</body>
</html>
