<?php namespace App\Http\Controllers;

use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

/**
 * Question Controller
 * 
 * @package Http
 * @subpackage Controllers
 */
class QuestionController extends Controller {

    /**
     * Answer Repository
     *
     * @var App\Repositories\QuestionRepository
     */
    protected $question;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(QuestionRepository $question) {
        //$this->middleware('guest');
        $this->middleware('checkMember');
        $this->middleware('setLoc');
        $this->question = $question;
    }

    public function oneQuestion($questionSlug) {
        $data = $this->question->getQuestionBySlug($questionSlug);
        return view('question', $data);
    }

    public function publish(Request $request) {
        return $this->question->publishQuestion($request);
    }

}
