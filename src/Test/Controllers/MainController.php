<?php

namespace Test\Controllers;

use Test\Models\Articles\Article;

class MainController extends AbstractController
{
    /** Find and show all Articles */
    public function main()
    {
        $articles = Article::findAll();
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }

}