document.addEventListener('DOMContentLoaded', () => {
  const profile = document.querySelector(".profile");
  const settings = document.querySelector(".settings");

  if (profile && settings) {
      // Logika Toggle Menu
      profile.addEventListener("click", (e) => {
          e.stopPropagation();
          settings.classList.toggle("active");
      });

      document.addEventListener("click", (e) => {
          if (!profile.contains(e.target) && !settings.contains(e.target)) {
              settings.classList.remove("active");
          }
      });
  }
});