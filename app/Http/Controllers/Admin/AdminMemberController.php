<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminMemberRepository;
use Admin;
use App\Models\Member;
use App\Http\Requests\Admin\AdminMemberRequest;
use Redirect;

/**
 * Admin Member Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminMemberController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminMember;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminMemberRepository $adminMember) {
        $this->middleware('authOwl');
        $this->adminMember = $adminMember;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminMember->getMembers();
        $content = view('admin.members.index', $data)->render();
        $title = 'Admin - Members';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Member $member) {
        return view('admin.members.create', compact('member'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdminMemberRequest $request) {

        $data = $this->adminMember->storeNewMember($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/members?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.members.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = $this->adminMember->editMember($id);
        return view('admin.members.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AdminMemberRequest $request) {
        $data = $this->adminMember->updateMember($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/members?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.members.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for deleting the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function del($id) {
        return view('admin.members.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminMember->deleteMember($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/members?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.members.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminMember->allMembers();
        $content = view('admin.members.index', $data)->render();
        $title = 'Admin - Members';
        return Admin::view($content, $title);
    }

    public function changeVisibleMany() {
        $data = $this->adminMember->changeVisibleMany();
        return response()->json($data);
    }

    public function delMany() {
        $data = $this->adminMember->delMembers();
        if (isset($data['message'])) {

            if ($data['filterFlag'] === false) {
                return Redirect::to('admin/members?page=' . $data['page'])->with('message', $data['message']);
            } else {
                return Redirect::route('memberFilter', $data)->with($data);
            }
        } elseif (isset($data['error'])) {

            return Redirect::route('memberFilter', $data)->with($data);
        }
    }

    public function filter() {
        $data = $this->adminMember->filter();
        $content = view('admin.members.index', $data)->render();
        $title = 'Admin - Members';
        return Admin::view($content, $title);
    }

}
