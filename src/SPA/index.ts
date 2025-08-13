import Cookies from 'universal-cookie';

const cookies = new Cookies();
const content = document.getElementById('content');
const tokens = document.createElement('pre');

function showTokens() {
  tokens.innerHTML = JSON.stringify(
    cookies.get('tokens') || 'No token is stored',
    null,
    2
  );
}

const handlers = {
  authorize: () => {
    window.location.href = '/login/canvas/authorize';
  },

  refresh: async () => {
    await fetch('/login/canvas/refresh');
    showTokens();
  },

  deauthorize: (event: MouseEvent) => {
    cookies.set('tokens', '', {
      maxAge: 0,
      path: '/',
      partitioned: true,
      secure: true,
      sameSite: 'none'
    });
    (document.getElementById('authorize') as HTMLButtonElement).disabled =
      false;
    (document.getElementById('refresh') as HTMLButtonElement).disabled = true;
    (event.target as HTMLButtonElement).disabled = true;
    showTokens();
  }
};

(async () => {
  for (const text of Object.keys(handlers) as (keyof typeof handlers)[]) {
    const authorized = cookies.get('tokens');
    const button = document.createElement('button');
    button.id = text;
    button.innerText = text;
    button.classList.add('btn', 'm-3');
    switch (text) {
      case 'authorize':
        button.classList.add('btn-primary');
        break;
      case 'refresh':
        button.classList.add('btn-secondary');
        break;
      case 'deauthorize':
        button.classList.add('btn-danger');
        break;
    }
    button.addEventListener('click', handlers[text]);
    switch (text) {
      case 'authorize':
        if (authorized) {
          button.disabled = true;
        }
        break;
      default:
        if (!authorized) {
          button.disabled = true;
        }
    }

    content?.appendChild(button);
  }
  tokens.setAttribute('lang', 'json');
  content?.appendChild(tokens);

  showTokens();
})();
