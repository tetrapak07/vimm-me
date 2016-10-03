<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminRatingRepository;
use Admin;
use App\Models\Rating;
use Redirect;
use Illuminate\Http\Request;

/**
 * Admin Rating Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminRatingController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminRating;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminRatingRepository $adminRating) {
        $this->middleware('authOwl');
        $this->adminRating = $adminRating;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminRating->getRatings();
        $content = view('admin.ratings.index', $data)->render();
        $title = 'Admin - Ratings';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Rating $rating) {
        $members = $this->adminRating->getMembers();
        return view('admin.ratings.create', compact('rating', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {

        $data = $this->adminRating->storeNewRating($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/ratings?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.ratings.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = $this->adminRating->editRating($id);
        return view('admin.ratings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request) {
        $data = $this->adminRating->updateRating($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/ratings?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.ratings.index')->with('error', $data['error']);
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
        return view('admin.ratings.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminRating->deleteRating($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/ratings?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.ratings.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminRating->allRatings();
        $content = view('admin.ratings.index', $data)->render();
        $title = 'Admin - Ratings';
        return Admin::view($content, $title);
    }

    public function changeVisibleMany() {
        $data = $this->adminRating->changeVisibleMany();
        return response()->json($data);
    }

    public function delMany() {
        $data = $this->adminRating->delRatings();
        if (isset($data['message'])) {

            if ($data['filterFlag'] === false) {
                return Redirect::to('admin/ratings?page=' . $data['page'])->with('message', $data['message']);
            } else {
                return Redirect::route('ratingFilter', $data)->with($data);
            }
        } elseif (isset($data['error'])) {

            return Redirect::route('ratingFilter', $data)->with($data);
        }
    }

    public function filter() {
        $data = $this->adminRating->filter();
        $content = view('admin.ratings.index', $data)->render();
        $title = 'Admin - Ratings';
        return Admin::view($content, $title);
    }

}
