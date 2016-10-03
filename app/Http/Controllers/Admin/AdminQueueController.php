<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminQueueRepository;
use Admin;
use App\Models\Queue;
use App\Http\Requests\Admin\AdminQueueRequest;
use Redirect;

/**
 * Admin Queue Controller
 * 
 * @package Controllers
 * @subpackage Admin
 */
class AdminQueueController extends Controller {

    /**
     * Admin Repository
     *
     * @var App\Repositories\Admin\AdminRepository
     */
    protected $adminQueue;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminQueueRepository $adminQueue) {
        $this->middleware('authOwl');
        $this->adminQueue = $adminQueue;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $data = $this->adminQueue->siteQueues();
        $content = view('admin.queues.index', $data)->render();
        $title = 'Admin - Queues';
        return Admin::view($content, $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Queue $queue) {
        return view('admin.queues.create', compact('queue'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdminQueueRequest $request) {

        $data = $this->adminQueue->storeNewQueue($request);
        if (isset($data['message'])) {
            return Redirect::to('admin/queues?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.queues.index')->with('error', $data['error']);
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
        $data = $this->adminQueue->editQueues($id);
        return view('admin.queues.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, AdminQueueRequest $request) {
        $data = $this->adminQueue->updateQueue($id, $request);
        if (isset($data['message'])) {
            return Redirect::to('admin/queues?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.queues.index')->with('error', $data['error']);
        }
    }

    /**
     * Show the form for deleting the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function del($id) {
        return view('admin.queues.del', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $data = $this->adminQueue->deleteQueue($id);
        if (isset($data['message'])) {
            return Redirect::to('admin/queues?page=' . $data['page'])->with('message', $data['message']);
        } elseif (isset($data['error'])) {
            return Redirect::route('admin.queues.index')->with('error', $data['error']);
        }
    }

    public function all() {
        $data = $this->adminQueue->allQueue();
        $content = view('admin.queues.index', $data)->render();
        $title = 'Admin - Queues';
        return Admin::view($content, $title);
    }

}
