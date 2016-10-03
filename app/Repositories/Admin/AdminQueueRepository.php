<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Queue as QueueModel;
use App\Repositories\QueueRepository;
use Input;

/**
 * Admin Queue Repository
 * 
 * Repository for custom methods of Admin Queue model
 * 
 * @package   Repositories
 * @author    Den
 */
class AdminQueueRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(QueueModel $model, QueueRepository $queue) {

        $this->model = $model;
        $this->queue = $queue;
    }

    public function siteQueues() {
        $queues = $this->queue->getQueuesPaginate();
        return ['queues' => $queues];
    }

    public function editQueues($queueId) {
        $queue = $this->queue->getQueueById($queueId);
        return ['queue' => $queue];
    }

    public function storeNewQueue($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $ret = $this->queue->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Queue created', 'page' => $this->queue->pageCount()];
        } else {
            return ['error' => 'Error while Queue created'];
        }
    }

    public function deleteQueue($id) {
        $ret = $this->queue->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if ($page > $this->queue->pageCount()) {
                $page = $this->queue->pageCount();
            }
            return ['message' => 'Queue was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Queue deleted'];
        }
    }

    public function updateQueue($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->queue->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Queue was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Queue deleted'];
        }
    }

    public function allQueue() {
        $queues = $this->queue->getAll();
        return ['queues' => $queues, 'all' => 'all'];
    }

}
