<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminQuestionRepository;
use Admin;
use App\Models\Question;
use App\Http\Requests\Admin\AdminQuestionRequest;
use Redirect;

/**
 * Admin Question Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminQuestionController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminQuestion;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminQuestionRepository $adminQuestion) {
        $this->middleware('authOwl');
        $this->adminQuestion = $adminQuestion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminQuestion->getQuestions();
        $content = view('admin.questions.index', $data)->render();
        $title = 'Admin - Video Questions';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Question $question) {
        $members = $this->adminQuestion->getMembers();
        return view('admin.questions.create', compact('question', 'members', 'ratings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdminQuestionRequest $request) {

        $data = $this->adminQuestion->storeNewQuestion($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/questions?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.questions.index')->with('error', $data['error']);
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
        $data = $this->adminQuestion->editQuestion($id);
        return view('admin.questions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AdminQuestionRequest $request) {
        $data = $this->adminQuestion->updateQuestion($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/questions?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.questions.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for deleting the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function del($id) {
        return view('admin.questions.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminQuestion->deleteQuestion($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/questions?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.questions.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminQuestion->allQuestions();
        $content = view('admin.questions.index', $data)->render();
        $title = 'Admin - Questions';
        return Admin::view($content, $title);
    }

    public function changeVisibleMany() {
        $data = $this->adminQuestion->changeVisibleMany();
        return response()->json($data);
    }

    public function delMany() {
        $data = $this->adminQuestion->delQuestions();
        if (isset($data['message'])) {

            if ($data['filterFlag'] === false) {
                return Redirect::to('admin/questions?page=' . $data['page'])->with('message', $data['message']);
            } else {
                return Redirect::route('questionFilter', $data)->with($data);
            }
        } elseif (isset($data['error'])) {

            return Redirect::route('questionFilter', $data)->with($data);
        }
    }

    public function filter() {
        $data = $this->adminQuestion->filter();
        $content = view('admin.questions.index', $data)->render();
        $title = 'Admin - Questions';
        return Admin::view($content, $title);
    }

}
