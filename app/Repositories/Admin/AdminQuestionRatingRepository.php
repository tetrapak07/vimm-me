<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
use App\Repositories\QuestionRatingRepository;
use App\Repositories\MemberRepository;
use App\Repositories\QuestionRepository;
use Input;
use URL;

/**
 * Admin Member Repository
 * 
 * Repository for custom methods of Admin Member model
 * 
 * @package   Repositories
 * @author    Den
 */
class AdminQuestionRatingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, QuestionRatingRepository $questionRating, QuestionRepository $question, MemberRepository $member
    ) {

        $this->model = $model;
        $this->question = $question;
        $this->questionRating = $questionRating;
        $this->member = $member;
    }

    public function getMembers() {
        return $this->member->getAllNoBannedMembers();
    }

    public function getQuestions() {
        return $this->question->getAllVisibleQuestions();
    }

    public function getQuestionRatings() {
        $questionRates = $this->questionRating->getQuestionRatingsPaginate();
        $members = $this->getMembers();
        $questions = $this->getQuestions();
        return ['questionRatings' => $questionRates, 'members' => $members, 'questions' => $questions];
    }

    public function allQuestionRatings() {
        $questionRates = $this->questionRating->getAll();
        $members = $this->getMembers();
        $questions = $this->getQuestions();
        return ['questionRatings' => $questionRates, 'all' => 'all', 'members' => $members, 'questions' => $questions];
    }

    public function storeNewQuestionRating($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $ret = $this->questionRating->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Question Rating was created', 'page' => $this->questionRating->pageCount()];
        } else {
            return ['error' => 'Error while Question Rating created'];
        }
    }

    public function delQuestionRatings() {
        $input = array_except(Input::all(), ['_token']);
        $memberSel = (int) $input['memberSel'];
        $questionSel = (int) $input['questionSel'];
        $page = (int) $input['page'];
        $ids = explode(',', $input['hashes']);

        $url = URL::previous();
        $urlParts = explode('/admin/questions-rating/filter', $url);
        $filterFlag = false;
        if (isset($urlParts[1])) {
            $filterFlag = true;
        }

        $ret = $this->questionRating->delByIds($ids);
        if ($ret) {
            if ($page > $this->questionRating->pageCount()) {
                $page = $this->questionRating->pageCount();
            }
            return ['message' => 'Question Rating was deleted', 'page' => $page, 'memberSel' => $memberSel, 'questionSel' => $questionSel, 'filterFlag' => $filterFlag];
        } else {
            return ['error' => 'Error while Question Rating deleted', 'page' => $page, 'memberSel' => $memberSel, 'questionSel' => $questionSel];
        }
    }

    public function editQuestionRating($questionId) {
        $qrating = $this->questionRating->getQuestionRatingById($questionId);
        $members = $this->getMembers();
        $questions = $this->getQuestions();
        return ['qrating' => $qrating, 'members' => $members, 'questions' => $questions];
    }

    public function deleteQuestionRating($id) {
        $ret = $this->questionRating->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if ($page > $this->questionRating->pageCount()) {
                $page = $this->questionRating->pageCount();
            }
            return ['message' => 'Question Rating was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Question Rating deleted'];
        }
    }

    public function filter() {
        $input = array_except(Input::all(), ['_token']);
        if (isset($input['page'])) {
            $page = (int) $input['page'];
        } else {
            $page = 1;
        }
        if (isset($input['limit'])) {
            $limit = (int) $input['limit'];
        } else {

            $limit = 100;
        }
        $offset = ($page - 1) * $limit;
        if (isset($input['sort']) && ($input['sort']) == 'on') {

            $sort = 'desc';
        } else {
            $sort = 'asc';
        }
        if (isset($input['memberSel'])) {
            $memberSel = (int) $input['memberSel'];
        } else {
            $memberSel = 0;
        }

        if (isset($input['questionSel'])) {
            $questionSel = (int) $input['questionSel'];
        } else {
            $questionSel = 0;
        }

        $data = $this->questionRating->getFilteredQuestionRatingData($limit, $memberSel, $questionSel, $sort, $offset);
        $members = $this->getMembers();
        $data['members'] = $members;
        $data['memberSel'] = $memberSel;
        $questions = $this->getQuestions();
        $data['questions'] = $questions;
        $data['questionSel'] = $questionSel;
        return $data;
    }

    public function updateQuestionRating($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->questionRating->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Question Rating was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Question Rating updated'];
        }
    }

}
