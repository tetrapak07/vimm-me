<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\AnswerRating as AnswerRatingModel;

/**
 * Member Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class AnswerRatingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AnswerRatingModel $model) {

        $this->model = $model;
        $this->countPerPage = 10;
    }

    public function getAnswerRatingsPaginate($count = 10) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function getAnswerRatingById($id) {
        return $this->getById($id);
    }

    public function getFilteredAnswerRatingData($limit, $member, $answer, $sort = 'desc', $offset = 0) {
        $answerRatings = $this->model
                ->where(function($query) use ($member) {
                    if ($member != '') {
                        $query->where('member_id', $member);
                    }
                }
                )
                ->where(function($query) use ($answer) {
                    if ($answer != '') {
                        $query->where('answer_id', $answer);
                    }
                }
                )
                ->orderBy('id', $sort)
                ->take($limit)
                ->skip($offset)
                ->get();

        return ['answerRatings' => $answerRatings, 'limit' => $limit, 'sort' => $sort];
    }

}
