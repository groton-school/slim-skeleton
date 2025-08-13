import '@qui-cli/env/1Password.js';

import gcloud from '@battis/partly-gcloudy';
import input from '@inquirer/input';
import { Colors } from '@qui-cli/colors';
import { Core } from '@qui-cli/core';
import { Log } from '@qui-cli/log';
import { Root } from '@qui-cli/root';
import { Shell } from '@qui-cli/shell';
import { Validators } from '@qui-cli/validators';
import path from 'node:path';

(async () => {
  Root.configure({ root: path.dirname(import.meta.dirname) });
  const {
    values: { force }
  } = await Core.init({
    flag: {
      force: {
        short: 'f',
        default: false
      }
    }
  });
  const configure = force || !process.env.PROJECT;

  const { project, appEngine } = await gcloud.batch.appEngineDeployAndCleanup({
    retainVersions: 2
  });

  if (configure) {
    await gcloud.services.enable(gcloud.services.API.CloudFirestoreAPI);
    await gcloud.services.enable(gcloud.services.API.CloudLoggingAPI);
    const [{ name: database }] = JSON.parse(
      Shell.exec(
        `gcloud firestore databases list --project=${project.projectId} --format=json --quiet`
      )
    );
    Shell.exec(
      `gcloud firestore databases update --type=firestore-native --database="${database}" --project=${project.projectId} --format=json --quiet`
    );

    // store Canvas credentials in a secret
    const redirectUri = `https://${appEngine.defaultHostname}/login/canvas/redirect`;
    Log.info(
      `You need to create Developer API Key in canvas with the redirect URI ${Colors.url(
        redirectUri
      )}\n\nIf you haven't done that before, follow these directions: ${Colors.url(
        'https://community.canvaslms.com/t5/Admin-Guide/How-do-I-add-a-developer-API-key-for-an-account/ta-p/259'
      )}`
    );

    await gcloud.batch.secretsSetAndCleanUp({
      name: 'CANVAS_CREDENTIALS',
      value: JSON.stringify({
        canvasInstanceUrl: await input({
          message: 'Canvas instance URL',
          validate: Validators.notEmpty
        }),
        clientId: await input({
          message: 'Canvas API Key client ID',
          validate: Validators.notEmpty
        }),
        clientSecret: await input({
          message: 'Canvas API Key client secret',
          validate: Validators.notEmpty
        }),
        redirectUri
      }),
      retainVersions: 1
    });
    await gcloud.secrets.enableAppEngineAccess();
  }

  Log.info(
    `Install your LTI by adding an LTI Registration in Developer Keys for ${Colors.url(
      `https://${appEngine.defaultHostname}/lti/register`
    )}\n\nIf you haven't done that before, follow these directions: ${Colors.url(
      'https://community.canvaslms.com/t5/Admin-Guide/How-do-I-add-a-developer-LTI-Registration-key-for-an-account/ta-p/601370'
    )}\n\nYou will then need to enable the app following these directions: ${Colors.url(
      'https://community.canvaslms.com/t5/Admin-Guide/How-do-I-configure-an-external-app-for-an-account-using-a-client/ta-p/202'
    )}`
  );
})();
