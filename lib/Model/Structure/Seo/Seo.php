<?php

namespace Headless\Model\Structure\SEO;

use Headless\Model\Media\Media;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @Type()
 */
class Seo
{
    public ?\rex_article      $article = null;
    public ?\rex_yrewrite_seo $seo     = null;

    /**
     * @Field()
     */
    public function getTitle(): ?string
    {
        if ($this->seo) {
            return $this->seo->getTitle();
        }
        return null;
    }


    /**
     * @Field()
     */
    public function getDescription(): ?string
    {
        if ($this->seo) {
            return $this->seo->getDescription();
        }
        return null;
    }

    /**
     * @Field()
     */
    public function getCanonical(): ?string
    {
        if ($this->seo) {
            return $this->seo->getCanonicalUrl();
        }
        return null;
    }

    /**
     * @Field()
     */
    public function getRobots(): ?string
    {
        if ($this->seo) {
            $index = $this->article->getValue(\rex_yrewrite_seo::$meta_index_field) ?? \rex_yrewrite_seo::$index_setting_default;
            if (1 == $index || (0 == $index && $this->article->isOnline())) {
                return 'index, follow';
            } else if (2 == $index) {
                return 'noindex, follow';
            }
            return 'noindex, nofollow';
        }
        return null;
    }

    /**
     * @Field()
     * @return LangUrl[]
     */
    public function getAlternateLanguages(): array
    {
        if ($this->seo) {
            $hrefLangs = $this->seo->getHrefLangs();
            $result = [];
            foreach($hrefLangs as $code => $url) {
                $result[] = new LangUrl($code, $url);
            }
            return $result;
        }
        return [];
    }

    /**
     * @Field()
     */
    public function getImage(): ?Media
    {
        if ($this->seo) {
            $images = explode(',',$this->seo->getImages());
            if (count($images) > 0 && $images[0]) {
                return Media::getByName($images[0], 'yrewrite_seo_image');
            }
        }
        return null;
    }


    public static function getByArticle(\rex_article $article): ?Seo
    {
        $seo          = new Seo();
        $seoData      = new \rex_yrewrite_seo($article->getId());
        $seo->article = $article;
        $seo->seo     = $seoData;
        return $seo;
    }

    public static function getByArticleId(int $articleId): ?Seo
    {
        $article = \rex_article::get($articleId);
        if ($article instanceof \rex_article) {
            return self::getByArticle($article);
        }
        return null;
    }
}
