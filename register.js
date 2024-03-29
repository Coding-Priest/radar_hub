document.addEventListener('DOMContentLoaded', () => {
    const register_button = document.getElementById('submit');
    const login = document.getElementById('login_link');
  
    register_button.addEventListener('click', async (event) => {
      event.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const response = await fetch('/radar_hub/api/register.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });
      if (response.ok) {
        window.location.href = 'index.html';
      } else {
        const error = await response.json();
        alert(error.message);
      }
    });
  
    login.addEventListener('click', async (event) => {
      event.preventDefault();
      window.location.href = 'login.html';
    });
  });