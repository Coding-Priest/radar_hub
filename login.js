document.addEventListener('DOMContentLoaded', () => {
    const login_button = document.getElementById('submit');
    const register = document.getElementById('register_link');
  
    login_button.addEventListener('click', async (event) => {
      event.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const response = await fetch('api/test_login.php', {
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
  
    register.addEventListener('click', async (event) => {
      event.preventDefault();
      window.location.href = 'register.html';
    });
  });