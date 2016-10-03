<?php namespace App\Http\Controllers;

use App\Repositories\WelcomeRepository;
use App\Repositories\MemberRepository;
use Input;

class WelcomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Welcome Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders the "marketing page" for the application and
      | is configured to only allow guests. Like most of the other sample
      | controllers, you are free to modify or remove it as you desire.
      |
     */

    /**
     * Welcome Repository
     *
     * @var App\Repositories\WelcomeRepository
     */
    protected $welcome;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WelcomeRepository $welcome, MemberRepository $member) {

        $this->middleware('checkMember');
        $this->middleware('setLoc');
        $this->welcome = $welcome;
        $this->member = $member;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index() {

        $page = Input::get('page') ? Input::get('page') : 1;
        $data = $this->welcome->getDataForIndexPage($page);
        return view('welcome', $data);
    }

    public function indexApi($page = 0) {
        $data = $this->welcome->getStartDataForApi($page);
        echo json_encode($data);
    }

    public function checkNewsApi() {
        //$this->middleware('checkMember');
        $data = $this->welcome->checkNewsApi();
        echo json_encode($data);
    }

    public function changeLocale($lang) {
        \App::setLocale($lang);
        $this->member->checkCacheLocale($lang);
        return redirect()->route('indx');
    }

}
