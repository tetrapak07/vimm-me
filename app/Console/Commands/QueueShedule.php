<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Queue Shedule Class for cron
 * 
 * @package Console
 * @subpackage Commands
 * @author    Den
 */
class QueueShedule extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queueshedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue Shedule';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $run_command = false;
        $monitor_file_path = storage_path('queue.pid');

        if (file_exists($monitor_file_path)) {
            $pid = file_get_contents($monitor_file_path);
            $result = exec("ps -p $pid --no-heading | awk '{print $1}'");

            if ($result == '') {
                $run_command = true;
            }
        } else {
            $run_command = true;
        }

        if ($run_command) {
            $command = 'php ' . base_path('artisan') . ' queue:listen --timeout=3600 > /dev/null & echo $!';
            $number = exec($command);
            file_put_contents($monitor_file_path, $number);
        }
    }

}
