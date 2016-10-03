<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
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
class AdminMemberRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, MemberRepository $member
    ) {

        $this->model = $model;
        $this->member = $member;
    }

    public function getMembers() {
        $members = $this->member->getMembersPaginate();
        return ['members' => $members];
    }

    public function editMember($memberId) {
        $member = $this->member->getMemberById($memberId);
        return ['member' => $member];
    }

    public function storeNewMember($request) {
        $input = array_except(Input::all(), ['_token', 'page']);
        $input = $this->prepareForStoreMember($input);
        $ret = $this->member->save($input);

        if ($ret->exists == '1') {
            return ['message' => 'Member created', 'page' => $this->member->pageCount()];
        } else {
            return ['error' => 'Error while Member created'];
        }
    }

    private function prepareForStoreMember($input) {


        if ($input['visible'] == '') {
            $input['visible'] = 1;
        }

        return $input;
    }

    public function deleteMember($id) {
        $ret = $this->member->deleteById($id);
        $page = Input::get('page');
        if ($ret) {
            if ($page > $this->member->pageCount()) {
                $page = $this->member->pageCount();
            }
            return ['message' => 'Member was deleted', 'page' => $page];
        } else {
            return ['error' => 'Error while Member deleted'];
        }
    }

    public function updateMember($id, $request) {
        $page = Input::get('page');
        $data = array_except(Input::all(), ['_token', '_method', 'page']);
        $ret = $this->member->updateById($id, $data);
        if ($ret) {
            return ['message' => 'Member was updated', 'page' => $page];
        } else {
            return ['error' => 'Error while Member deleted'];
        }
    }

    public function allMembers() {
        $members = $this->member->getAll();
        return ['members' => $members, 'all' => 'all'];
    }

    public function changeVisibleMany() {
        $input = array_except(Input::all(), ['_token']);
        $ids = explode(',', $input['ids']);
        $status = (int) $input['visible'];
        $res = $this->member->visibleByIds($ids, $status);
        if ($res) {
            $data = ['status' => 'ok', 'message' => 'Member(s) visible change success!'];
        } else {
            $data = ['status' => 'error', 'message' => 'Member(s) visible change error!'];
        }
        return $data;
    }

    public function delMembers() {
        $input = array_except(Input::all(), ['_token']);
        $page = (int) $input['page'];
        $ids = explode(',', $input['hashes']);

        $url = URL::previous();
        $urlParts = explode('/admin/members/filter', $url);
        $filterFlag = false;
        if (isset($urlParts[1])) {
            $filterFlag = true;
        }

        $ret = $this->member->delByIds($ids);
        if ($ret) {
            if ($page > $this->member->pageCount()) {
                $page = $this->member->pageCount();
            }
            return ['message' => 'Members was deleted', 'page' => $page, 'filterFlag' => $filterFlag];
        } else {
            return ['error' => 'Error while Members deleted', 'page' => $page];
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

        $data = $this->member->getFilteredMemberData($limit, $sort, $offset);
        return $data;
    }

}
