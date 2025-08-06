<div class="container">
    <h2>User ID <?= $user->getId() ?></h2>
    <pre lang="json"><?= json_encode($user, JSON_PRETTY_PRINT) ?></pre>
</div>