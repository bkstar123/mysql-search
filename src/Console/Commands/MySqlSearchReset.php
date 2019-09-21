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

use Illuminate\Console\Command;
use Bkstar123\MySqlSearch\Console\Commands\BaseMySqlSearch;

class MySqlSearchReset extends BaseMySqlSearch
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql-search:reset {modelClass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove fulltext index from a MySQL database table' ;

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
        if (!$this->hasFullTextIndex()) {
            return $this->line("The table '{$this->table}' does not have the 'fulltext_index' index");
        }
        $this->removeFullTextIndex();
    }
}
