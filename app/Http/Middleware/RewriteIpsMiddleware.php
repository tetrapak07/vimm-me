<?php namespace App\Http\Middleware;

use Closure;
use App\Repositories\MemberRepository;
use App\Repositories\AnswerRepository;
use App\Repositories\QuestionRepository;

class RewriteIpsMiddleware {

    /**
     * The Member implementation.
     *
     * @var Guard
     */
    protected $member;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $member
     * @return void
     */
    public function __construct(MemberRepository $member, QuestionRepository $question, AnswerRepository $answer) {
        $this->member = $member;
        $this->question = $question;
        $this->answer = $answer;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $questions = $this->question->getAllQ();
        foreach ($questions as $question) {
            $urlWithIp = $question->url;
            $urlThumbWithIp = $question->url_thumb;
            echo '<br>$urlWithIp: ' . $urlWithIp;
            $urlThumbWithIpExplode = explode('/', $urlThumbWithIp);
            $ip = $urlThumbWithIpExplode[2];
            echo ' ip:' . $ip;
            if (isset($this->member->checkIsExistMemberByIp($ip)[0]['id'])) {
                $memberId = $this->member->checkIsExistMemberByIp($ip)[0]['id'];
                echo ' $memberId:' . $memberId;
                $newUrl = str_replace($ip, $memberId, $urlWithIp);
                $newUrlThumb = str_replace($ip, $memberId, $urlThumbWithIp);
                echo ' $newUrl:' . $newUrl;

                $r = $this->member->renameDirs($ip, $memberId);
                $this->question->updateById($question->id, ['url' => $newUrl, 'url_thumb' => $newUrlThumb]);
            }
        }
        $answers = $this->answer->getAllA();
        foreach ($answers as $answer) {
            $urlWithIp = $answer->url;
            $urlThumbWithIp = $answer->url_thumb;
            echo '<br>$urlWithIp: ' . $urlWithIp;
            $urlThumbWithIpExplode = explode('/', $urlThumbWithIp);
            $ip = $urlThumbWithIpExplode[2];
            echo ' ip:' . $ip;
            if (isset($this->member->checkIsExistMemberByIp($ip)[0]['id'])) {
                $memberId = $this->member->checkIsExistMemberByIp($ip)[0]['id'];
                echo ' $memberId:' . $memberId;
                $newUrl = str_replace($ip, $memberId, $urlWithIp);
                $newUrlThumb = str_replace($ip, $memberId, $urlThumbWithIp);
                echo ' $newUrl:' . $newUrl;

                $r = $this->member->renameDirs($ip, $memberId);
                $this->answer->updateById($answer->id, ['url' => $newUrl, 'url_thumb' => $newUrlThumb]);
               
            }
        }
        
        return $next($request);
    }

}
