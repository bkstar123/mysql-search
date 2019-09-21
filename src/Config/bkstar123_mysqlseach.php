<?php
/**
 * bkstar123_mysqlseach.php
 *
 * @author: tuanha
 * @last-mod: 21-Sept-2019
 */

return [
    /**
     * fulltext search mode, valid values:
     * 'natural' for naturla language mode,
     * 'boolean' for boolean mode
     * 'query' for query expansion mode
     */
    'fulltext_mode' => env('FULLTEXT_SEARCH_MODE', 'natural'),
];
