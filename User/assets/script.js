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

  const audio = document.getElementById("audio");
  const progress = document.getElementById("progress");
  const currentTimeDisplay = document.getElementById("currentTime");
  const durationDisplay = document.getElementById("duration");
  const playPauseButton = document.getElementById("playPauseButton");
  
  function formatTime(seconds) {
      const min = Math.floor(seconds / 60);
      const sec = Math.floor(seconds % 60);
      return `${min}:${sec < 10 ? '0' : ''}${sec}`;
  }
  
  function togglePlayPause() {
      if (audio.paused) {
          audio.play();
          playPauseButton.textContent = "⏸️";
      } else {
          audio.pause();
          playPauseButton.textContent = "▶️";
      }
  }
  
  function rewind() {
      audio.currentTime -= 5;
  }
  
  function forward() {
      audio.currentTime += 5;
  }
  
  audio.addEventListener("loadedmetadata", () => {
      durationDisplay.textContent = formatTime(audio.duration);
  });
  
  audio.addEventListener("timeupdate", () => {
      progress.max = audio.duration;
      progress.value = audio.currentTime;
      currentTimeDisplay.textContent = formatTime(audio.currentTime);
  });
  
  progress.addEventListener("input", () => {
      audio.currentTime = progress.value;
  });

  audio.addEventListener("play", () => {
      playPauseButton.textContent = "⏸️";
  });

  audio.addEventListener("pause", () => {
      playPauseButton.textContent = "▶️";
  });
  