<?php include __DIR__ . '/../header.php'; ?>
    <div class="article-title">
        <h1><?= $article->getName() ?></h1>
    </div>
    <p><?= $article->getText() ?></p>
    <i>Автор статьи: <a href="/users/<?= $article->getAuthor()->getNickname() ?>"><?= $article->getAuthor()->getNickname() ?></a></i>
<?php include __DIR__ . '/../footer.php'; ?>