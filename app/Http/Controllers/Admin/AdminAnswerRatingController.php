<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminAnswerRatingRepository;
use Admin;
use App\Models\AnswerRating;
use App\Http\Requests\Admin\AdminAnswerRatingRequest;
use Redirect;

/**
 * Admin Answer Rating Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminAnswerRatingController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminAnswerRating;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminAnswerRatingRepository $adminAnswerRating) {
        $this->middleware('authOwl');
        $this->adminAnswerRating = $adminAnswerRating;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminAnswerRating->getAnswerRatings();
        $content = view('admin.answers-rating.index', $data)->render();
        $title = 'Admin - Answer Ratings';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(AnswerRating $qrating) {
        $members = $this->adminAnswerRating->getMembers();
        $answers = $this->adminAnswerRating->getAnswers();
        return view('admin.answers-rating.create', compact('qrating', 'members', 'answers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdminAnswerRatingRequest $request) {

        $data = $this->adminAnswerRating->storeNewAnswerRating($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/answers-rating?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.answers-rating.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = $this->adminAnswerRating->editAnswerRating($id);
        return view('admin.answers-rating.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AdminAnswerRatingRequest $request) {
        $data = $this->adminAnswerRating->updateAnswerRating($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/answers-rating?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.answers-rating.index')->with('error', $data['error']);
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
        return view('admin.answers-rating.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminAnswerRating->deleteAnswerRating($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/answers-rating?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.answers-rating.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminAnswerRating->allAnswerRatings();
        $content = view('admin.answers-rating.index', $data)->render();
        $title = 'Admin - Answer Ratings';
        return Admin::view($content, $title);
    }

    public function changeVisibleMany() {
        $data = $this->adminAnswerRating->changeVisibleMany();
        return response()->json($data);
    }

    public function delMany() {
        $data = $this->adminAnswerRating->delAnswerRatings();
        if (isset($data['message'])) {

            if ($data['filterFlag'] === false) {
                return Redirect::to('admin/answers-rating?page=' . $data['page'])->with('message', $data['message']);
            } else {
                return Redirect::route('memberFilter', $data)->with($data);
            }
        } elseif (isset($data['error'])) {

            return Redirect::route('memberFilter', $data)->with($data);
        }
    }

    public function filter() {
        $data = $this->adminAnswerRating->filter();
        $content = view('admin.answers-rating.index', $data)->render();
        $title = 'Admin - Answer Ratings';
        return Admin::view($content, $title);
    }

}
