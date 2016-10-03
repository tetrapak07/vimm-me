<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;
use App\Repositories\MemberRepository;
use App\Repositories\RatingRepository;
use Input;
use URL;

/**
 * Admin Question Repository
 * 
 * Repository for custom methods of Admin Question model
 * 
 * @package   Repositories
 * @author    Den
 */
class AdminQuestionRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, QuestionRepository $question, MemberRepository $member, AnswerRepository $answer, RatingRepository $rating
    ) {

        $this->model = $model;
        $this->question = $question;
        $this->member = $member;
        $this->rating = $rating;
        $this->answer = $answer;
    }

    public function getQuestions() {
        $questions = $this->question->getQuestionsPaginate();
        $members = $this->getMembers();
        $ratings = $this->getRatings();
        return ['questions' => $questions, 'members' => $members, 'ratings' => $ratings];
    }

    public function getRatings() {
        return $this->rating->getRatings();
    }

    public function getMembers() {
        return $this->member->getAllNoBannedMembers();
    }

    public function editQuestion($questionId) {
        $question = $this->question->getQuestionById($questionId);
        $members = $this->getMembers();
        $ratings = $this->getRatings();
        return ['question' => $question, 'members' => $members, 'ratings' => $ratings];
    }

    public function storeNewQuestion($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $input = $this->question->prepareForStoreQuestion($input);

        $ret = $this->question->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Question created', 'page' => $this->question->pageCount()];
        } else {
            return ['error' => 'Error while Question created'];
        }
    }

    public function deleteQuestion($id) {
        $quest = $this->question->getFirstById($id);
        $answers = $this->answer->getAnswersByQuestionsId($id);
        //print_r($answers);exit;
        $ret = $this->question->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if (isset($quest->id)) {
                \File::delete(public_path() . '/' . $quest->url, public_path() . '/' . $quest->url_thumb);
            }
            foreach ($answers as $answer) {
                \File::delete(public_path() . '/' . $answer->url, public_path() . $answer->url_thumb);
            }
            if ($page > $this->question->pageCount()) {
                $page = $this->question->pageCount();
            }
            return ['message' => 'Question was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Question deleted'];
        }
    }

    public function updateQuestion($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->question->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Question was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Question deleted'];
        }
    }

    public function allQuestions() {
        $questions = $this->question->getAll();
        $members = $this->getMembers();
        $ratings = $this->getRatings();
        return ['questions' => $questions, 'all' => 'all', 'members' => $members, 'ratings' => $ratings];
    }

    public function changeVisibleMany() {
        $input = array_except(Input::all(), ['_token']);
        $ids = explode(',', $input['ids']);
        $status = (int) $input['visible'];
        $res = $this->question->visibleByIds($ids, $status);
        if ($res) {
            $data = ['status' => 'ok', 'message' => 'Question(s) visible change success!'];
        } else {
            $data = ['status' => 'error', 'message' => 'Question(s) visible change error!'];
        }
        return $data;
    }

    public function delQuestions() {
        $input = array_except(Input::all(), ['_token']);
        $memberSel = (int) $input['memberSel'];
        $page = (int) $input['page'];
        $ids = explode(',', $input['hashes']);

        $url = URL::previous();
        $urlParts = explode('/admin/questions/filter', $url);
        $filterFlag = false;
        if (isset($urlParts[1])) {
            $filterFlag = true;
        }

        foreach ($ids as $qid) {
            $answers = $this->answer->getAnswersByQuestionsId($qid);
            foreach ($answers as $answer) {
                \File::delete(public_path() . '/' . $answer->url, public_path() . $answer->url_thumb);
            }
        }

        $ret = $this->question->delByIds($ids);
        if ($ret) {
            if ($page > $this->question->pageCount()) {
                $page = $this->question->pageCount();
            }
            return ['message' => 'Questions was deleted', 'page' => $page, 'memberSel' => $memberSel, 'filterFlag' => $filterFlag];
        } else {
            return ['error' => 'Error while Questions deleted', 'page' => $page, 'memberSel' => $memberSel];
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

        if (isset($input['ratingSel'])) {
            $ratingSel = (int) $input['ratingSel'];
        } else {
            $ratingSel = 0;
        }

        $data = $this->question->getFilteredQuestionData($limit, $memberSel, $ratingSel, $sort, $offset);
        $members = $this->getMembers();
        $data['members'] = $members;
        $data['memberSel'] = $memberSel;
        $ratings = $this->getRatings();
        $data['ratings'] = $ratings;
        $data['ratingSel'] = $ratingSel;
        return $data;
    }

}
