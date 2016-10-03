<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Member as MemberModel;
use Request;
use File;

/**
 * Member Repository
 *
 * @package   Repositories
 * @author    Den
 */
class MemberRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(MemberModel $model) {

        $this->model = $model;
        $this->countPerPage = 10;
    }

    public function getAllNoBannedMembers() {
        return $this->model
                        ->where('visible', 1)
                        ->orderBy('id', 'DESC')
                        ->get();
    }

    public function getMembersPaginate($count = 10) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function getMemberById($id) {
        return $this->getById($id);
    }

    public function getFilteredMemberData($limit, $sort = 'desc', $offset = 0) {
        $members = $this->model
                ->orderBy('id', $sort)
                ->take($limit)
                ->skip($offset)
                ->get();

        return ['members' => $members, 'limit' => $limit, 'sort' => $sort];
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function checkMember() {
        $ip = Request::ip();
        $ret = $this->checkIsExistMemberByIp($ip);

        if (isset($ret[0])) {
            $isNoBanned = $ret[0]['visible'];

            if ($isNoBanned) {
                return true;
            } else {
                return false;
            }
        } else {
            $r = $this->saveNewMemberByIp($ip);
            if ($r) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function checkIsExistMemberByIp($ip) {
        $member = $this->model
                        ->where('ip', $ip)
                        ->take(1)
                        ->get()->toArray();
        return $member;
    }

    private function saveNewMemberByIp($ip) {
        $input = array('visible' => '1', 'ip' => $ip);
        $ret = $this->save($input);
        if ($ret->exists == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function getThisMember() {
        $ip = Request::ip();
        return $this->checkIsExistMemberByIp($ip);
    }

    public function saveMemberRating($memberId, $ratingId) {
        return $this->model
                        ->where('id', $memberId)
                        ->with('ratings')
                        ->attach([$ratingId])
                        ->save();
    }

    public function makeUserDir($param = '') {
        $memberId = $this->getThisMember()[0]['id'];
        $path = 'uploads/' . $memberId . $param;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
            File::makeDirectory($path . '/tmp', $mode = 0777, true, true);
        }
        if (!File::exists($path)) {
            return false;
        } else {
            return $path;
        }
    }

    public function checkOrCreateTmpDir($param = '') {
        $memberId = $this->getThisMember()[0]['id'];
        $path = 'uploads/' . $memberId . $param . '/tmp';
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        if (!File::exists($path)) {
            return false;
        } else {
            return $path;
        }
    }

    public function temp($tempIp) {
        $ip = Request::ip();
        if ($ip == $tempIp) {
            return true;
        } else {
            return false;
        }
    }

    public function getLocale() {
        if (isset($this->getThisMember()[0]['rem'])) {
            $localeCache = $this->getThisMember()[0]['rem'];
        } else {
            $localeCache = '';
        }
        $ip = Request::ip();
        if (!isset($localeCache)OR ( $localeCache == '')) {
            $location = \GeoIP::getLocation();
            if ($location['isoCode'] == 'UA') {
                $locale = 'ua';
            } else if ($location['isoCode'] == 'RU') {
                $locale = 'ru';
            } else {
                $locale = 'en';
            }
            $this->model->where('ip', '=', $ip)->update(['rem' => $locale]);
        } else {
            $locale = $localeCache;
        }
        return $locale;
    }

    public function checkCacheLocale($lang) {

        $ip = Request::ip();
        $this->model->where('ip', '=', $ip)->update(['rem' => $lang]);
    }

    public function renameDirs($oldName, $newName) {
        $fullPathOld = public_path() . '/uploads/' . $oldName . '/';
        $fullPathNew = public_path() . '/uploads/' . $newName . '/';
        if (file_exists($fullPathOld)) {
            return rename($fullPathOld, $fullPathNew);
        }
    }

    public function saveUserCache($lastQuestionId, $lastAnswerId, $lastYourAnswerId) {
        $memberId = $this->getThisMember()[0]['id'];
        $lastQuestionKey = $memberId . 'question';
        $lastAnswerKey = $memberId . 'answer';
        $lastYourAnswerKey = $memberId . 'answeryou';
        \Cache::forever($lastQuestionKey, $lastQuestionId);
        \Cache::forever($lastAnswerKey, $lastAnswerId);
        \Cache::forever($lastYourAnswerKey, $lastYourAnswerId);
    }

}
