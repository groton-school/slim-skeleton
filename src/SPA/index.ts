import { Canvas } from '@groton/canvas-api.client.web';

type Action = {
  name: string;
  classList: string[];
  handler: EventListener;
};

// initialize Canvas API proxy
Canvas.init();

const content = document.getElementById('content');
const display_id = 'display';

const Authorize = 'Authorize';
const actions: Action[] = [
  {
    name: Authorize,
    classList: ['btn-primary'],
    handler: () => Canvas.authorize()
  },
  {
    name: 'Deauthorize',
    classList: ['btn-danger'],
    handler: () => Canvas.deauthorize()
  },
  {
    name: 'Owner',
    classList: ['btn-secondary'],
    handler: async () => {
      const user = await Canvas.getOwner();
      const display =
        document.getElementById(display_id) || document.createElement('pre');
      display.id = display_id;
      display.innerHTML = JSON.stringify(user, null, 2);
      content?.appendChild(display);
    }
  },
  {
    name: 'Courses',
    classList: ['btn-secondary'],
    handler: async () => {
      const courses = await Canvas.v1.Users.Courses.list({
        pathParams: { user_id }
      });
      const display =
        document.getElementById(display_id) || document.createElement('pre');
      display.id = display_id;
      display.innerHTML = JSON.stringify(courses, null, 2);
      content?.appendChild(display);
    }
  }
];

(async () => {
  // detect not authorized or authorized as different user (e.g. masquerading)
  const owner = await Canvas.getOwner();
  if (owner && owner.id != user_id) {
    await Canvas.deauthorize();
  }

  // display actions
  for (const action of actions) {
    const button = document.createElement('button');
    button.innerText = action.name;
    button.classList.add('btn', 'm-2', ...action.classList);
    button.disabled =
      (action.name === Authorize && !!owner) ||
      (action.name !== Authorize && !owner);
    button.addEventListener('click', action.handler);
    content?.appendChild(button);
  }
})();
