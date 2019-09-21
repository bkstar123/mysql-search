<?php
/**
 * MySqlSearch Trait
 *
 * @author: tuanha
 * @last-mod: 21-Sept-2019
 */
namespace Bkstar123\MySqlSearch\Traits;

trait MySqlSearch
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string  $term
     */
    public function scopeSearch($query, $term = '', $fts = true, $mode = '')
    {
        return $fts ? $this->fulltextSearch($query, $term, $mode) : $this->wildcardSearch($query, $term);
    }
    
    /**
     * @param string $term
     * @return string
     */
    protected function filterSearchTerm($term)
    {
        return htmlentities($term);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string  $term
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    protected function fulltextSearch($query, $term = '', $mode = '')
    {
        if (!empty($term) &&
            property_exists($this, 'mysqlSearchable') &&
            is_array(static::$mysqlSearchable) &&
            !empty(static::$mysqlSearchable)) {
            $columns = implode(',', static::$mysqlSearchable);
            $mode = !empty($mode) ? $mode : config('bkstar123_mysqlseach.fulltext_mode');
            switch ($mode) {
                case 'natural':
                    $textMode = 'IN NATURAL LANGUAGE MODE';
                    break;
                case 'boolean':
                    $textMode = 'IN BOOLEAN MODE';
                    break;
                case 'query':
                    $textMode = 'WITH QUERY EXPANSION';
                    break;
                default:
                    $textMode = 'IN NATURAL LANGUAGE MODE';
                    break;
            }
            $query->whereRaw("MATCH ({$columns}) AGAINST (:term {$textMode})", [
                'term' => $this->filterSearchTerm($term)
            ]);
        }
        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string  $term
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    protected function wildcardSearch($query, $term = '')
    {
        if (!empty($term) &&
            property_exists($this, 'mysqlSearchable') &&
            is_array(static::$mysqlSearchable) &&
            !empty(static::$mysqlSearchable)) {
            foreach (static::$mysqlSearchable as $attribute) {
                $query = $query->orWhere($attribute, 'like', "%{$term}%");
            }
        }
        return $query;
    }
}
