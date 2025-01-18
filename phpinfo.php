<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Simple Layout</title>
</head>
<body>
    <div class="top-bar">
        <h2>Witam, Admin!</h2>
        <a href="#">Wyloguj</a>
    </div>
    <div class="container">
        <nav>
            <h2>Nawigacja</h2>
            <a href="strona1.html">Przycisk 1</a>
            <a href="strona2.html">Przycisk 2</a>
            <a href="strona3.html">Przycisk 3</a>
            <a href="strona4.html">Przycisk 4</a>
        </nav>
        <div class="content">
            <h1>Treść Strony</h1>
            <p>To jest przykładowy tekst w sekcji treści. Możesz tutaj wstawić dowolne informacje, takie jak formularz, tabelki czy obrazy.</p>
            <div class="form-container">
                <form>
                    <div class="form-group">
                        <label for="name">Imię:</label>
                        <input type="text" id="name" name="name" placeholder="Wpisz swoje imię">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Wpisz swój email">
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" placeholder="Wpisz swoje hasło">
                    </div>
                    <div class="form-group">
                        <label for="file">Załącz plik:</label>
                        <input type="file" id="file" name="file">
                    </div>
                    <div class="form-group">
                        <label>Wybierz płeć:</label>
                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Mężczyzna</label>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Kobieta</label>
                    </div>
                    <div class="form-group">
                        <label for="hobbies">Wybierz swoje hobby:</label>
                        <input type="checkbox" id="sports" name="hobbies" value="sports">
                        <label for="sports">Sport</label>
                        <input type="checkbox" id="music" name="hobbies" value="music">
                        <label for="music">Muzyka</label>
                        <input type="checkbox" id="travel" name="hobbies" value="travel">
                        <label for="travel">Podróże</label>
                    </div>
                    <div class="form-group">
                        <label for="country">Wybierz kraj:</label>
                        <select id="country" name="country">
                            <option value="pl">Polska</option>
                            <option value="us">USA</option>
                            <option value="de">Niemcy</option>
                            <option value="fr">Francja</option>
                        </select>
                    </div>
                    <button type="submit">Wyślij</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
