<?php namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Repositories\Admin\AdminRepository;

/**
 * Count Content Information Class
 * 
 * @package Commands
 * @author    Den
 */
class MakeSitemap extends Command implements SelfHandling, ShouldBeQueued {

  use InteractsWithQueue,
      SerializesModels;

  /**
   *
   * @var admin
   */
  private $admin;

  /**
   * Create a new command instance.
   * 
   * @param AdminRepository $admin
   * @return void
   */
  public function __construct(AdminRepository $admin) {

    $this->admin = $admin;
  }

  /**
   * Execute the command.
   * 
   * @param object $job
   * @return void
   */
  public function fire($job, $data) {
    if ((!isset($data['domain']))OR($data['domain']==''))  {
    $adress =  env('APP_URL');
    } else {
     $adress = 'https://' . trim($data['domain']);
    }
    echo '$adress: '.$adress;
    $this->admin->sitemapGenCommand($adress);
    $job->delete();
  }

}


