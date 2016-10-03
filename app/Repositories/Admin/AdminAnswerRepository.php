<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
use App\Repositories\AnswerRepository;
use App\Repositories\MemberRepository;
use App\Repositories\RatingRepository;
use App\Repositories\QuestionRepository;
use Input;
use URL;

/**
 * Admin Answer Repository
 * 
 * Repository for custom methods of Admin Answer model
 * 
 * @package   Repositories
 * @author    Den
 */
class AdminAnswerRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, AnswerRepository $answer, QuestionRepository $question, RatingRepository $rating, MemberRepository $member
    ) {

        $this->model = $model;
        $this->answer = $answer;
        $this->question = $question;
        $this->member = $member;
        $this->rating = $rating;
    }

    public function getAnswers() {
        $answers = $this->answer->getAnswersPaginate();
        $members = $this->getMembers();
        $questions = $this->getQuestions();
        $ratings = $this->getRatings();
        return ['answers' => $answers, 'members' => $members, 'questions' => $questions, 'ratings' => $ratings];
    }

    public function getRatings() {
        return $this->rating->getRatings();
    }

    public function getMembers() {
        return $this->member->getAllNoBannedMembers();
    }

    public function getQuestions() {
        return $this->question->getAllVisibleQuestions();
    }

    public function editAnswer($answerId) {
        $answer = $this->answer->getAnswerById($answerId);
        $members = $this->getMembers();
        $questions = $this->getQuestions();
        $ratings = $this->getRatings();
        return ['answer' => $answer, 'members' => $members, 'questions' => $questions, 'ratings' => $ratings];
    }

    public function storeNewAnswer($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $input = $this->answer->prepareForStoreAnswer($input);
        $ret = $this->answer->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Answer created', 'page' => $this->answer->pageCount()];
        } else {
            return ['error' => 'Error while Answer created'];
        }
    }

    public function deleteAnswer($id) {
        $answ = $this->answer->getFirstById($id);
        $ret = $this->answer->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if (isset($answ->id)) {
                \File::delete(public_path() . '/' . $answ->url, public_path() . '/' . $answ->url_thumb);
            }
            if ($page > $this->answer->pageCount()) {
                $page = $this->answer->pageCount();
            }
            return ['message' => 'Answer was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Answer deleted'];
        }
    }

    public function updateAnswer($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->answer->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Answer was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Answer deleted'];
        }
    }

    public function allAnswers() {
        $answers = $this->answer->getAll();
        $members = $this->getMembers();
        $questions = $this->getQuestions();
        $ratings = $this->getRatings();
        return ['answers' => $answers, 'all' => 'all', 'members' => $members, 'questions' => $questions, 'ratings' => $ratings];
    }

    public function changeVisibleMany() {
        $input = array_except(Input::all(), ['_token']);
        $ids = explode(',', $input['ids']);
        $status = (int) $input['visible'];
        $res = $this->answer->visibleByIds($ids, $status);
        if ($res) {
            $data = ['status' => 'ok', 'message' => 'Answer(s) visible change success!'];
        } else {
            $data = ['status' => 'error', 'message' => 'Answer(s) visible change error!'];
        }
        return $data;
    }

    public function delAnswers() {
        $input = array_except(Input::all(), ['_token']);
        $memberSel = (int) $input['memberSel'];
        $questionSel = (int) $input['questionSel'];
        $page = (int) $input['page'];
        $ids = explode(',', $input['hashes']);

        $url = URL::previous();
        $urlParts = explode('/admin/answers/filter', $url);
        $filterFlag = false;
        if (isset($urlParts[1])) {
            $filterFlag = true;
        }

        $ret = $this->answer->delByIds($ids);
        if ($ret) {
            if ($page > $this->answer->pageCount()) {
                $page = $this->answer->pageCount();
            }
            return ['message' => 'Answers was deleted', 'page' => $page, 'memberSel' => $memberSel, 'questionSel' => $questionSel, 'filterFlag' => $filterFlag];
        } else {
            return ['error' => 'Error while Answers deleted', 'page' => $page, 'memberSel' => $memberSel, 'questionSel' => $questionSel];
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

        if (isset($input['ratingSel'])) {
            $ratingSel = (int) $input['ratingSel'];
        } else {
            $ratingSel = 0;
        }

        $data = $this->answer->getFilteredAnswerData($limit, $memberSel, $questionSel, $ratingSel, $sort, $offset);
        $members = $this->getMembers();
        $data['members'] = $members;
        $data['memberSel'] = $memberSel;
        $questions = $this->getQuestions();
        $data['questions'] = $questions;
        $data['questionSel'] = $questionSel;
        $ratings = $this->getRatings();
        $data['ratings'] = $ratings;
        $data['ratingSel'] = $ratingSel;
        return $data;
    }

}
