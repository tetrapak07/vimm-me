<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminQuestionRatingRepository;
use Admin;
use App\Models\QuestionRating;
use App\Http\Requests\Admin\AdminQuestionRatingRequest;
use Redirect;

/**
 * Admin Question Rating Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminQuestionRatingController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminQuestionRating;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminQuestionRatingRepository $adminQuestionRating) {
        $this->middleware('authOwl');
        $this->adminQuestionRating = $adminQuestionRating;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminQuestionRating->getQuestionRatings();
        $content = view('admin.questions-rating.index', $data)->render();
        $title = 'Admin - Question Ratings';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(QuestionRating $qrating) {
        $members = $this->adminQuestionRating->getMembers();
        $questions = $this->adminQuestionRating->getQuestions();
        return view('admin.questions-rating.create', compact('qrating', 'members', 'questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdminQuestionRatingRequest $request) {

        $data = $this->adminQuestionRating->storeNewQuestionRating($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/questions-rating?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.questions-rating.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = $this->adminQuestionRating->editQuestionRating($id);
        return view('admin.questions-rating.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AdminQuestionRatingRequest $request) {
        $data = $this->adminQuestionRating->updateQuestionRating($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/questions-rating?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.questions-rating.index')->with('error', $data['error']);
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
     * Show the form for deleting the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function del($id) {
        return view('admin.questions-rating.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminQuestionRating->deleteQuestionRating($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/questions-rating?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.questions-rating.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminQuestionRating->allQuestionRatings();
        $content = view('admin.questions-rating.index', $data)->render();
        $title = 'Admin - Question Ratings';
        return Admin::view($content, $title);
    }

    public function changeVisibleMany() {
        $data = $this->adminQuestionRating->changeVisibleMany();
        return response()->json($data);
    }

    public function delMany() {
        $data = $this->adminQuestionRating->delQuestionRatings();
        if (isset($data['message'])) {

            if ($data['filterFlag'] === false) {
                return Redirect::to('admin/questions-rating?page=' . $data['page'])->with('message', $data['message']);
            } else {
                return Redirect::route('memberFilter', $data)->with($data);
            }
        } elseif (isset($data['error'])) {

            return Redirect::route('memberFilter', $data)->with($data);
        }
    }

    public function filter() {
        $data = $this->adminQuestionRating->filter();
        $content = view('admin.questions-rating.index', $data)->render();
        $title = 'Admin - Question Ratings';
        return Admin::view($content, $title);
    }

}
