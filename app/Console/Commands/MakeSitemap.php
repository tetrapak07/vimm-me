<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Make Sitemap command Class
 * 
 * @package Console
 * @subpackage Commands
 * @author    Den
 */
class MakeSitemap extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'sitemap';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Make Site Sitemap';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {

    $this->comment('Sitemap making...');
   // $domain = $this->argument('domain');
    \Queue::push('\App\Commands\MakeSitemap'/*, ['domain' => $domain]*/);
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
 /* protected function getArguments() {
    return array(
      array('domain', InputArgument::REQUIRED, 'Domain'),
    );
  }*/

}