import '@qui-cli/env/1Password.js';

import gcloud from '@battis/partly-gcloudy';
import { Colors } from '@qui-cli/colors';
import { Core } from '@qui-cli/core';
import { Log } from '@qui-cli/log';
import { Root } from '@qui-cli/root';
import { Shell } from '@qui-cli/shell';
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
