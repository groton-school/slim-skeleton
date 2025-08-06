<?php

use Packback\Lti1p3\LtiConstants;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $tool_name ?></title>
</head>

<body>
    <div class="container">
        <h1><?= $tool_name ?></h1>
        <p>The app has been started.</p>
    </div>
    <?= isset($launchData) ? $this->fetch('./partial/launchData.php', [
        'messageType' => $launchData[LtiConstants::MESSAGE_TYPE],
        'launchData' => $launchData
    ]) : '' ?>
</body>

</html>