<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Logowanie - Audiobooki</title>
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
    <h1>Logowanie</h1>
    <form>
      <label for="username">Nazwa użytkownika:</label>
      <input type="text" id="username" name="username" placeholder="Wpisz nazwę użytkownika" required>
      
      <label for="password">Hasło:</label>
      <input type="password" id="password" name="password" placeholder="Wpisz hasło" required>
      
      <button type="submit">Zaloguj się</button>
    </form>
  </div>
</body>
</html>
