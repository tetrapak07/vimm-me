<?php namespace App\Http\Controllers;

use App\Repositories\AnswerRepository;
use Illuminate\Http\Request;

/**
 * Answer Controller
 * 
 * @package Http
 * @subpackage Controllers
 */
class AnswerController extends Controller {

    /**
     * Answer Repository
     *
     * @var App\Repositories\AnswerRepository
     */
    protected $answer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AnswerRepository $answer) {
        $this->middleware('checkMember');
        $this->middleware('setLoc');
        $this->answer = $answer;
    }

    /**
     * Show more answers.
     *
     * @return Response
     */
    public function more(Request $request) {
        return $this->answer->moreAnswers($request);
    }

    public function oneAnswer($answerSlug) {
        $data = $this->answer->getAnswerBySlug($answerSlug);
        return view('answer', $data);
    }

    public function publish(Request $request) {
        return $this->answer->publishAnswer($request);
    }

    public function showForApi($id, $page = 0) {

        $data = $this->answer->showForApi($id, $page);

        echo json_encode($data);
    }

}
