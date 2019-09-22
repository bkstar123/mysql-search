# bkstar123/mysql-search

> A lightweight package to enable full-text search and partial search for laravel/mysql applications  

## 1. Requirements
- PHP 7.1.3+  
- Laravel 5.5+  
- MySQL 5.6+  

## 2. Installtion
    composer require bkstar123/mysql-search  

## 3. Usage

For example, your database has an ```articles``` table with ```id, title, content, status, user_id, created_at, updated_at``` columns.  

You want to allow users to search for a term/keyword against ```title, content``` columns.  

In order to do so, just do the following steps:  
a) In ```Article``` model, import & use the trait ```Bkstar123\MySqlSearch\Traits\MySqlSearch```  
b) In ```Article``` model, define a public static property named as ```$mysqlSearchable```, for instance:  
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Bkstar123\MySqlSearch\Traits\MySqlSearch;

class Article extends Model
{
    use MySqlSearch;

    public static $mysqlSearchable = ['title', 'content'];

    //...
}

```

c) If you want to use MySQL full-text search feature (supported by MyIASM, InnoDB - since MySQL 5.6+ storage engines), the below additional works are to be fulfilled:  

-> Create FullTEXT index for ```articles``` table:  
```php artisan mysql-search:init "App\Article"```  

**Note**: To remove FULLTEXT index from ```articles``` table:  
```php artisan mysql-search:reset "App\Article"```  

d) Search query  

***Full text search (default)***:  
```php
<?php
App\Article::search($searchTerms, true, 'natural')
App\Article::search($searchTerms) // full-text search in natural language mode
App\Article::search($searchTerms, true, 'boolean') // Full-text search in boolean mode
App\Article::search($searchTerms, true, 'query') // Full-text search with query expansion
```
By default, the default full-text search mode is NATURAL LANGUAGE. You can change this default value by using FULLTEXT_SEARCH_MODE variable in ```.env``` file, possible values: ```natural, boolean, query```  

Refer to https://www.w3resource.com/mysql/mysql-full-text-search-functions.php for details on natual language & boolean mode. The query expansion mode is not recommended due to the fact that it creates a lot of noise in the search result.  

The biggest problem with full-text indexes is that thay do not play nicely with regular indexes. If you do a full-text search query in combination with using other normal indexes, you are going to have a very inefficient query (bad performance). Refer to https://medium.com/@kirkbackus/using-full-text-index-for-innodb-when-a-search-engine-is-not-feasible-d666830b4000  

***Partial search (using the operator LIKE %$searchTerms%)***
```php
<?php
App\Article::search($searchTerms, false) // partial search with LIKE operator againt wildcard terms e.g: %searchTerm% 
```



