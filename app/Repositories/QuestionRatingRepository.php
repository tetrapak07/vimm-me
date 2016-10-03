<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\QuestionRating as QuestionRatingModel;

/**
 * Member Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class QuestionRatingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(QuestionRatingModel $model) {

        $this->model = $model;
        $this->countPerPage = 10;
    }

    public function getQuestionRatingsPaginate($count = 10) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function getQuestionRatingById($id) {
        return $this->getById($id);
    }

    public function getFilteredQuestionRatingData($limit, $member, $question, $sort = 'desc', $offset = 0) {
        $questionRatings = $this->model
                ->where(function($query) use ($member) {
                    if ($member != '') {
                        $query->where('member_id', $member);
                    }
                }
                )
                ->where(function($query) use ($question) {
                    if ($question != '') {
                        $query->where('question_id', $question);
                    }
                }
                )
                ->orderBy('id', $sort)
                ->take($limit)
                ->skip($offset)
                ->get();

        return ['questionRatings' => $questionRatings, 'limit' => $limit, 'sort' => $sort];
    }

}
