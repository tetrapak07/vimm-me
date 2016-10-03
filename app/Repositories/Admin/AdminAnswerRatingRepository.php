<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
use App\Repositories\AnswerRatingRepository;
use App\Repositories\MemberRepository;
use App\Repositories\AnswerRepository;
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
class AdminAnswerRatingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, AnswerRatingRepository $answerRating, AnswerRepository $answer, MemberRepository $member
    ) {

        $this->model = $model;
        $this->answer = $answer;
        $this->answerRating = $answerRating;
        $this->member = $member;
    }

    public function getMembers() {
        return $this->member->getAllNoBannedMembers();
    }

    public function getAnswers() {
        return $this->answer->getAllVisibleAnswers();
    }

    public function getAnswerRatings() {
        $answerRates = $this->answerRating->getAnswerRatingsPaginate();
        $members = $this->getMembers();
        $answers = $this->getAnswers();
        return ['answerRatings' => $answerRates, 'members' => $members, 'answers' => $answers];
    }

    public function allAnswerRatings() {
        $answerRates = $this->answerRating->getAll();
        $members = $this->getMembers();
        $answers = $this->getAnswers();
        return ['answerRatings' => $answerRates, 'all' => 'all', 'members' => $members, 'answers' => $answers];
    }

    public function storeNewAnswerRating($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $ret = $this->answerRating->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Answer Rating was created', 'page' => $this->answerRating->pageCount()];
        } else {
            return ['error' => 'Error while Answer Rating created'];
        }
    }

    public function delAnswerRatings() {
        $input = array_except(Input::all(), ['_token']);
        $memberSel = (int) $input['memberSel'];
        $answerSel = (int) $input['answerSel'];
        $page = (int) $input['page'];
        $ids = explode(',', $input['hashes']);

        $url = URL::previous();
        $urlParts = explode('/admin/answers-rating/filter', $url);
        $filterFlag = false;
        if (isset($urlParts[1])) {
            $filterFlag = true;
        }

        $ret = $this->answerRating->delByIds($ids);
        if ($ret) {
            if ($page > $this->answerRating->pageCount()) {
                $page = $this->answerRating->pageCount();
            }
            return ['message' => 'Answer Rating was deleted', 'page' => $page, 'memberSel' => $memberSel, 'answerSel' => $answerSel, 'filterFlag' => $filterFlag];
        } else {
            return ['error' => 'Error while Answer Rating deleted', 'page' => $page, 'memberSel' => $memberSel, 'answerSel' => $answerSel];
        }
    }

    public function editAnswerRating($answerId) {
        $qrating = $this->answerRating->getAnswerRatingById($answerId);
        $members = $this->getMembers();
        $answers = $this->getAnswers();
        return ['qrating' => $qrating, 'members' => $members, 'answers' => $answers];
    }

    public function deleteAnswerRating($id) {
        $ret = $this->answerRating->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if ($page > $this->answerRating->pageCount()) {
                $page = $this->answerRating->pageCount();
            }
            return ['message' => 'Answer Rating was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Answer Rating deleted'];
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

        if (isset($input['answerSel'])) {
            $answerSel = (int) $input['answerSel'];
        } else {
            $answerSel = 0;
        }

        $data = $this->answerRating->getFilteredAnswerRatingData($limit, $memberSel, $answerSel, $sort, $offset);
        $members = $this->getMembers();
        $data['members'] = $members;
        $data['memberSel'] = $memberSel;
        $answers = $this->getAnswers();
        $data['answers'] = $answers;
        $data['answerSel'] = $answerSel;
        return $data;
    }

    public function updateAnswerRating($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->answerRating->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Answer Rating was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Answer Rating updated'];
        }
    }

}
