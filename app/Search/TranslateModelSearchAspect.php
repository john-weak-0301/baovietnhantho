<?php

namespace App\Search;

use Illuminate\Support\Arr;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Searchable\ModelSearchAspect;

class TranslateModelSearchAspect extends ModelSearchAspect
{
    protected function addSearchConditions(Builder $query, string $term)
    {
        /* @var \Spatie\Searchable\SearchableAttribute[] $attributes */
        $attributes = $this->attributes;

        $searchTerm = mb_strtolower($term, 'UTF8');

        if (preg_match_all('/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $term, $matches)) {
            $searchTerms = $this->parseSearchTerms($matches[0]);

            // if the search string has only short terms or stopwords,
            // or is 10+ terms long, match it as sentence.
            if (empty($searchTerms) || count($searchTerms) > 9) {
                $searchTerm = $term;
            } else {
                $searchTerm = implode(' ', $searchTerms);
            }
        }

        $query->where(function (Builder $query) use ($attributes, $searchTerm) {
            foreach (Arr::wrap($attributes) as $attribute) {
                $sql = "LOWER({$attribute->getAttribute()}) LIKE ?";

                $attribute->isPartial()
                    ? $query->orWhereRaw($sql, ["%{$searchTerm}%"])
                    : $query->orWhere($attribute->getAttribute(), $searchTerm);
            }
        });
    }

    protected function parseSearchTerms(array $terms): array
    {
        $checked   = [];
        $stopwords = $this->getStopWords();

        // Remove single stop-words.
        foreach ($terms as $term) {
            // keep before/after spaces when term is for exact match
            if (preg_match('/^".+"$/', $term)) {
                $term = trim($term, "\"'");
            } else {
                $term = trim($term, "\"' ");
            }

            // Avoid single A-Z and single dashes.
            if (!$term || (1 === strlen($term) && preg_match('/^[a-z\-]$/i', $term))) {
                continue;
            }

            if (in_array(mb_strtolower($term), $stopwords, true)) {
                continue;
            }

            $checked[] = $term;
        }

        if (empty($checked) || count($checked) > 9) {
            return $checked;
        }

        // Continue remove stop-words.
        $term = implode(' ', $checked);

        $stopwords = collect($stopwords)
            ->filter(function ($word) {
                return preg_match('/\s/', $word);
            })->all();

        $searchTerm = preg_replace(
            '~^\W*('.implode('|', array_map('preg_quote', $stopwords)).')\W+\b|\b\W+(?1)\W*$~i',
            '',
            $term
        );

        return array_filter(explode(' ', $searchTerm));
    }

    protected function getStopWords()
    {
        static $stopwords;

        if ($stopwords) {
            return $stopwords;
        }

        $stopwords = file(
            __DIR__.'/vietnamese-stopwords.txt',
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        return $stopwords;
    }
}
