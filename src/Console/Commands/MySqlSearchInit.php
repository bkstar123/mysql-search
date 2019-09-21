<?php
/**
 * MySqlSearch Command
 *
 * This command is to publish all neccessary configuration files for modifying the package's behavior
 *
 * @author: tuanha
 * @last-mod: 11-July-2019
 */
namespace Bkstar123\MySqlSearch\Console\Commands;

use Bkstar123\MySqlSearch\Console\Commands\BaseMySqlSearch;

class MySqlSearchInit extends BaseMySqlSearch
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql-search:init {modelClass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a MySQL database table to be full-text searchable' ;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->initializeData();
        if ($this->hasFullTextIndex()) {
            return $this->line("The table '{$this->table}' already has 'fulltext_index' index");
        }
        if (property_exists($this->modelClass, 'mysqlSearchable')) {
            if (is_array($this->modelClass::$mysqlSearchable) && !empty($this->modelClass::$mysqlSearchable)) {
                $this->createFullTextIndex();
            } else {
                return $this->error("{$this->modelClass}'s \$mysqlSearchable must be an array and not empty");
            }
        } else {
            return $this->error("{$this->modelClass} must define \$mysqlSearchable public static property");
        }
    }
}
