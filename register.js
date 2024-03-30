document.addEventListener('DOMContentLoaded', () => {
  if (window.eventListenersAdded) return;
  window.eventListenersAdded = true;

  const register_button = document.getElementById('submit');
  const login = document.getElementById('login_link');

  async function handleRegisterClick(event) {
    event.preventDefault();
    register_button.disabled = true;
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
      register_button.disabled = false;
    }
  }

  function handleLoginClick(event) {
    event.preventDefault();
    window.location.href = 'login.html';
  }

  register_button.removeEventListener('click', handleRegisterClick);
  login.removeEventListener('click', handleLoginClick);

  register_button.addEventListener('click', handleRegisterClick);
  login.addEventListener('click', handleLoginClick);
});