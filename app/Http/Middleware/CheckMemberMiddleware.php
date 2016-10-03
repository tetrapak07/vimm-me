<?php namespace App\Http\Middleware;

use Closure;
use App\Repositories\MemberRepository;

class CheckMemberMiddleware {

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
    public function __construct(MemberRepository $member) {
        $this->member = $member;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $ret = $this->member->checkMember(); //comment while not production

        /*         * **************Under construction BEGIN ********************* */
        $underConsruction = env('UNDER_CONSTRUCTION');
        if ($underConsruction) {
            $programmerIp = env('PROGRAMMER_IP');
            $return = $this->member->temp($programmerIp);
            if (!$return) {
                return view('under-construction');
            }
        }
        /*         * **************Under construction END ********************* */

        if (!$ret) {
            return view('banned');
        }
        return $next($request);
    }

}
