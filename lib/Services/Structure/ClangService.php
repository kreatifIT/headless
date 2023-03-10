<?php

namespace Headless\Services\Structure;

use Headless\Model\Structure\Clang;


class ClangService
{

    /**
     * Get all available languages
     *
     * @return Clang[]
     */
    public function getClangs(int $article): array
    {
        $article = \rex_article::get($article);
        $clangs = \rex_clang::getAll(1);
        return array_map(function($clang) use ($article) {
            $lang  = Clang::getByObject($clang);
            $lang->isActive = $clang->getId() === $article->getClangId();
            $lang->url = rex_getUrl($article->getId(), $clang->getId());
            return $lang;
        }, $clangs);
    }

}
