<?php

namespace Test\Models\Articles;

use Test\Exceptions\InvalidArgumentException;
use Test\Models\ActiveRecordEntity;
use Test\Models\Users\User;

/**
 * This is the model class for table "{{%articles}}".
 * @property int $id
 */

class Article extends ActiveRecordEntity
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $text;
    /** @var int */
    protected $authorId;
    /** @var string */
    protected $createdAt;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->authorId = $author->getId();
    }

    /**
     * Checking user rights (Edit & Delete Articles)
     * @param User $user
     * @return bool
     */
    public function isEditable(User $user): bool
    {
        return $user->isAdmin() || ($user->getId() === $this->getAuthorId());
    }

    /** @return string */
    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * Create a new Article
     * @param array $fields
     * @param User $author
     * @return Article
     * @throws InvalidArgumentException
     */
    public static function createFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new Article();
        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);
        $article->save();

        return $article;
    }

    /**
     * Update an Article
     * @param array $fields
     * @return Article
     * @throws InvalidArgumentException
     */
    public function updateFromArray(array $fields): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);
        $this->save();

        return $this;
    }

}