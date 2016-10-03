<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Setting as SettingModel;
use App\Repositories\MemberRepository;

/**
 * Setting Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class SettingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(SettingModel $model, MemberRepository $member) {

        $this->model = $model;
        $this->member = $member;
        $this->countPerPage = 5;
    }

    public function getAllSettings() {
        $locale = $this->member->getLocale();
        $res = $this->model->all()->toArray();
        $resArray = [];
        foreach ($res as $value) {
            if (($value['rem'] == $locale)OR ( $value['rem'] == '')) {
                $resArray[$value['name']] = $value['value'];
            }
        }
        $resObject = (object) $resArray;
        return $resObject;
    }

    public function getSettingsPaginate($count = 5) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function getSettingById($id) {
        return $this->getById($id);
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

}
