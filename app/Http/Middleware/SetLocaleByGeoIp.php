<?php namespace App\Http\Middleware;

use Closure;
use App\Repositories\MemberRepository;

class SetLocaleByGeoIp {

    public function __construct(MemberRepository $member) {
        $this->member = $member;
    }

    public function handle($request, Closure $next) {

        $locale = $this->member->getLocale();
        \App::setLocale($locale);
        return $next($request);
    }

}
