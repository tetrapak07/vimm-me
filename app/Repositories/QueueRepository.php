<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Queue as QueueModel;

/**
 * Queue Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class QueueRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(QueueModel $model) {

        $this->model = $model;
        $this->countPerPage = 5;
    }

    public function getQueuesPaginate($count = 5) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function getQueueById($id) {
        return $this->getById($id);
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

}
