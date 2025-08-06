<?php

use Packback\Lti1p3\LtiConstants;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $tool_name ?></title>
</head>

<body>
    <div class="container">
        <h1><?= $tool_name ?></h1>
        <p>The app has been started.</p>
    </div>
    <?= isset($user) ? $this->fetch('./partial/user.php', ['user' => $user]) : '' ?>
    <?= isset($launchData) ? $this->fetch('./partial/launchData.php', [
        'messageType' => $launchData[LtiConstants::MESSAGE_TYPE],
        'launchData' => $launchData
    ]) : '' ?>
</body>

</html>