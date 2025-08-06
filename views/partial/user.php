<div class="container">
    <h1>User ID <?= $user->getId() ?></h1>
    <pre lang="json"><?= json_encode($user, JSON_PRETTY_PRINT) ?></pre>
</div>