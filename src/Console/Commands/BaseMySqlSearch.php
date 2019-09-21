<?php
/**
 * BaseMySqlSearch
 *
 * @author: tuanha
 */
namespace Bkstar123\MySqlSearch\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

abstract class BaseMySqlSearch extends Command
{
    /**
     * Model Class for enabling full text search
     *
     * @var string
     */
    protected $modelClass;

    /**
     * The name of the underlying table
     *
     * @var string
     */
    protected $table;

    /**
     * The list of columns added to FULLTEXT index
     *
     * @var array
     */
    protected $columns;

    /**
     * Initialize the command data
     */
    protected function initializeData()
    {
        $this->modelClass = $this->argument('modelClass');
        if (is_subclass_of($this->modelClass, Model::class)) {
            $this->table = $this->modelClass::getQuery()->from;
        } else {
            throw new Exception("{$this->modelClass} must be a sub-class of " . Model::class);
        }
    }

    /**
     * Create FULLTEXT index
     */
    protected function createFullTextIndex()
    {
        $this->columns = implode(',', $this->modelClass::$mysqlSearchable);
        DB::statement("ALTER TABLE {$this->table} ADD FULLTEXT fulltext_index (".$this->columns.")");
        $this->info("The 'fulltext_index' has been created on the table '{$this->table}'");
    }

    /**
     * Remove FULLTEXT index
     */
    protected function removeFullTextIndex()
    {
        DB::statement("ALTER TABLE $this->table DROP INDEX fulltext_index");
        $this->info("The 'fulltext_index' has been removed from the table '{$this->table}'");
    }

    /**
     * Check if the given table already has fulltext_index index
     *
     * @return bool
     */
    protected function hasFullTextIndex()
    {
        $keys = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name='fulltext_index'"));
        return !empty($keys);
    }
}
