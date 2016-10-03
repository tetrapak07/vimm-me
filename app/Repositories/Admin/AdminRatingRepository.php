<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
use App\Repositories\RatingRepository;
use App\Repositories\MemberRepository;
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
class AdminRatingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, RatingRepository $rating, MemberRepository $member
    ) {

        $this->model = $model;
        $this->rating = $rating;
        $this->member = $member;
    }

    public function getMembers() {
        return $this->member->getAllNoBannedMembers();
    }

    public function getRatings() {
        $rates = $this->rating->getRatingsPaginate();
        $members = $this->getMembers();

        return ['ratings' => $rates, 'members' => $members];
    }

    public function allRatings() {
        $rates = $this->rating->getAll();
        $members = $this->getMembers();
        return ['ratings' => $rates, 'all' => 'all', 'members' => $members];
    }

    public function storeNewRating($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $ret = $this->rating->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Rating was created', 'page' => $this->rating->pageCount()];
        } else {
            return ['error' => 'Error while Rating created'];
        }
    }

    public function delRatings() {
        $input = array_except(Input::all(), ['_token']);
        $memberSel = (int) $input['memberSel'];
        $page = (int) $input['page'];
        $ids = explode(',', $input['hashes']);

        $url = URL::previous();
        $urlParts = explode('/admin/ratings/filter', $url);
        $filterFlag = false;
        if (isset($urlParts[1])) {
            $filterFlag = true;
        }

        $ret = $this->rating->delByIds($ids);
        if ($ret) {
            if ($page > $this->rating->pageCount()) {
                $page = $this->rating->pageCount();
            }
            return ['message' => 'Rating was deleted', 'page' => $page, 'memberSel' => $memberSel, 'filterFlag' => $filterFlag];
        } else {
            return ['error' => 'Error while Rating deleted', 'page' => $page, 'memberSel' => $memberSel];
        }
    }

    public function editRating($id) {
        $rating = $this->rating->getRatingById($id);
        $members = $this->getMembers();
        return ['rating' => $rating, 'members' => $members];
    }

    public function deleteRating($id) {
        $ret = $this->rating->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if ($page > $this->rating->pageCount()) {
                $page = $this->rating->pageCount();
            }
            return ['message' => 'Rating was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Rating deleted'];
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



        $data = $this->rating->getFilteredRatingData($limit, $memberSel, $sort, $offset);
        $members = $this->getMembers();
        $data['members'] = $members;
        $data['memberSel'] = $memberSel;
        return $data;
    }

    public function updateRating($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->rating->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Rating was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Rating updated'];
        }
    }

}
