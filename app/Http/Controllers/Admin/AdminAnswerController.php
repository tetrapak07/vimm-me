<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminAnswerRepository;
use Admin;
use App\Models\Answer;
use App\Http\Requests\Admin\AdminAnswerRequest;
use Redirect;

/**
 * Admin Answer Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminAnswerController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminAnswer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminAnswerRepository $adminAnswer) {
        $this->middleware('authOwl');
        $this->adminAnswer = $adminAnswer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminAnswer->getAnswers();
        $content = view('admin.answers.index', $data)->render();
        $title = 'Admin - Video Answers';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Answer $answer) {
        $members = $this->adminAnswer->getMembers();
        $questions = $this->adminAnswer->getQuestions();
        $ratings = $this->adminAnswer->getRatings();
        return view('admin.answers.create', compact('answer', 'members', 'questions', 'ratings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdminAnswerRequest $request) {

        $data = $this->adminAnswer->storeNewAnswer($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/answers?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.answers.index')->with('error', $data['error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = $this->adminAnswer->editAnswer($id);
        return view('admin.answers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AdminAnswerRequest $request) {
        $data = $this->adminAnswer->updateAnswer($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/answers?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.answers.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for deleting the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function del($id) {
        return view('admin.answers.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminAnswer->deleteAnswer($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/answers?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.answers.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminAnswer->allAnswers();
        $content = view('admin.answers.index', $data)->render();
        $title = 'Admin - Answers';
        return Admin::view($content, $title);
    }

    public function changeVisibleMany() {
        $data = $this->adminAnswer->changeVisibleMany();
        return response()->json($data);
    }

    public function delMany() {
        $data = $this->adminAnswer->delAnswers();
        if (isset($data['message'])) {

            if ($data['filterFlag'] === false) {
                return Redirect::to('admin/answers?page=' . $data['page'])->with('message', $data['message']);
            } else {
                return Redirect::route('answerFilter', $data)->with($data);
            }
        } elseif (isset($data['error'])) {

            return Redirect::route('answerFilter', $data)->with($data);
        }
    }

    public function filter() {
        $data = $this->adminAnswer->filter();
        $content = view('admin.answers.index', $data)->render();
        $title = 'Admin - Answers';
        return Admin::view($content, $title);
    }

}
