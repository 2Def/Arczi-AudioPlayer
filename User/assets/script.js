document.addEventListener('DOMContentLoaded', function() {
    // Obsługa kliknięcia w rozdziały
    const chapters = document.querySelectorAll('.chapter-item');
    const audioPlayer = document.getElementById('audioPlayer');
  
    chapters.forEach(chapter => {
      chapter.addEventListener('click', function() {
        // Usunięcie klasy aktywnej ze wszystkich elementów
        chapters.forEach(ch => ch.classList.remove('active'));
        // Ustawienie aktywnego rozdziału
        this.classList.add('active');
        // Pobranie źródła audio z atrybutu data-audio
        const audioSrc = this.getAttribute('data-audio');
        if (audioPlayer && audioSrc) {
          audioPlayer.src = audioSrc;
          audioPlayer.play();
        }
      });
    });
  });

  
  