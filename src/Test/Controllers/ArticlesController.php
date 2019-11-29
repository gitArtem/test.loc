<?php

namespace Test\Controllers;

use Test\Exceptions\ForbiddenException;
use Test\Exceptions\InvalidArgumentException;
use Test\Exceptions\NotFoundException;
use Test\Exceptions\UnauthorizedException;
use Test\Models\Articles\Article;
use Test\Models\Users\User;

class ArticlesController extends AbstractController
{
    /**
     * Show an Article
     * @param int $articleId
     * @throws NotFoundException
     */
    public function view(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        $reflector = new \ReflectionObject($article);
        $properties = $reflector->getProperties();
        $propertiesNames = [];
        foreach ($properties as $property) {
            $propertiesNames[] = $property->getName();
        }
        $isEditable = $article->isEditable($this->user);

        $this->view->renderHtml('articles/view.php', [
            'article' => $article,
            'isEditable' => $isEditable,
        ]);
    }

    /**
     * Add a new Article
     */
    public function add(): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$this->user->isAdmin() && !$this->user->isConfirmed()) {
            throw new ForbiddenException('Статьи могут добавлять только администраторы или подтвержденные пользователи');
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit;
        }
        $this->view->renderHtml('articles/add.php');
    }

    /**
     * Edit an Article
     * @param int $articleId
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$article->isEditable($this->user)) {
            throw new ForbiddenException('Статьи могут редактировать только авторы или администраторы ');
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', [
                    'error' => $e->getMessage(),
                    'article' => $article
                ]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    /**
     * Delete an Article
     * @param int $articleId
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function delete(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$article->isEditable($this->user)) {
            throw new ForbiddenException('Статьи могут удалять только авторы или администраторы ');
        }

        $article->delete();
        header('Location: /', true, 302);
        exit();
    }

}