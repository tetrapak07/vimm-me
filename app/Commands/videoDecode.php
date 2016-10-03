<?php namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;
use URL;
use File;

/**
 * Video Decode and save job class
 * 
 */
class videoDecode extends Command implements SelfHandling, ShouldBeQueued {

    use InteractsWithQueue,
        SerializesModels;

    /**
     *
     * @var user
     */
    private $question;
    private $answer;
    private $mobile = 0;
    protected $name = 'videoDecode';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(QuestionRepository $question, AnswerRepository $answer) {
        $this->question = $question;
        $this->answer = $answer;
    }

    private function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                is_dir($obj) ? removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    /**
     * Execute the command.
     * 
     * @param object $job
     * @param array $data
     * @return void
     */
    public function fire($job, $data) {


        $job->delete();
    }

}
