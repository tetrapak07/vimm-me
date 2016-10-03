<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Answer as AnswerModel;
use Input;
use App\Repositories\RatingRepository;
use App\Repositories\SettingRepository;
use Illuminate\Support\Str;
use Request;
use File;
use App\Repositories\MemberRepository;
use Carbon\Carbon;

/**
 * Answer Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class AnswerRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AnswerModel $model, RatingRepository $rating, SettingRepository $setting, MemberRepository $member) {

        $this->model = $model;
        $this->countPerPage = 10;
        $this->countPerPageFrontend = 3;
        $this->answersPerVideoLimit = 3;
        $this->answersPerVideoLimitAndroid = 3;
        $this->rating = $rating;
        $this->setting = $setting;
        $this->member = $member;
        $this->answersRestrictDay = 3;
    }

    public function getAllAnswers() {
        $res = $this->model->all()->toArray();
        $resArray = [];
        foreach ($res as $value) {
            $resArray[$value['name']] = $value['value'];
        }
        $resObject = (object) $resArray;
        return $resObject;
    }

    public function getMaxAnswerId() {
        return $this->getMax();
    }

    public function getMaxAnswerIdWithMemberId($memberId) {
        return $this->getMaxByOneParam('member_id', $memberId);
    }

    public function getAllVisibleAnswers() {
        return $this->model
                        ->with('rating')
                        ->where('visible', 1)
                        ->orderBy('id', 'DESC')
                        ->get();
    }

    public function getAllA() {
        return $this->model
                        ->orderBy('id', 'DESC')
                        ->get();
    }

    public function getAnswersPaginate($count = 10) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function getAnswerById($id) {
        return $this->getById($id);
    }

    public function getAnswersByQuestionsId($qid) {
        return $this->getAllItemsByOneParam('question_id', $qid);
    }

    public function getFilteredAnswerData($limit, $member, $question, $rating, $sort = 'desc', $offset = 0) {
        $answers = $this->model
                ->whereHas('member', function($query) use ($member) {
                    if ($member != '') {
                        $query->where('id', $member);
                    }
 
                })
                ->whereHas('question', function($query) use ($question) {
                    if ($question != '') {
                        $query->where('id', $question);
                    }
                })
                ->whereHas('rating', function($query) use ($rating) {
                    if ($rating != '') {
                        $query->where('id', $rating);
                    }
                })
                ->with(['member' => function($query) use ($member) {
                        if ($member != '') {
                            $query->where('id', $member);
                        }
                    },
                    'question' => function($query) use ($question) {
                        if ($question != '') {
                            $query->where('id', $question);
                        }
                    },
                    'rating' => function($query) use ($rating) {
                        if ($rating != '') {
                            $query->where('id', $rating);
                        }
                    },
                ])
                ->orderBy('id', $sort)
                ->take($limit)
                ->skip($offset)
                ->get();

        return ['answers' => $answers, 'limit' => $limit, 'memberSel' => $member, 'ratingSel' => $rating, 'sort' => $sort];
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function moreAnswers($request) {
        $data = Input::all();
        $questionId = (int) $data['question_id'];
        $offset = (int) $data['offset'];
        if (($offset !== 0)AND ( $questionId !== 0)) {

            $res = $this->model
                    ->where('question_id', $questionId)
                    ->with('rating')
                    ->take($this->answersPerVideoLimit)
                    ->skip($offset)
                    ->orderBy('id', 'DESC')
                    ->get();

            $answersView = view('_partials.answers', [
                'answers' => $res,
            ]);
            $content = $answersView->render();

            if ($res->count() >= 3) {
                return array('content' => $content, 'nextOffset' => $offset + $this->answersPerVideoLimit);
            } elseif (($res->count() <= 2)AND ( $res->count() > 0)) {
                return ['content' => $content, 'nextOffset' => 0];
            } elseif ($res->count() == 0) {
                return ['nextOffset' => 0];
            }
        } else {
            return ['error' => trans('messages.wrong-data')];
        }
    }

    public function getAnswerBySlug($slug) {
        $settings = $this->setting->getAllSettings();
        $answer = $this->model
                ->where('visible', 1)
                ->where('slug', $slug)
                ->first();
        if (!isset($answer->id)) {
            \App::abort(404);
        }

        $data = [
            'settings' => $settings,
            'answer' => $answer
        ];
        return $data;
    }

    public function prepareForStoreAnswer($input) {
        $input['slug'] = Str::slug($input['title']) . '-' . mt_rand(1, 1000000);

        if ($input['visible'] == '') {
            $input['visible'] = 1;
        }

        if ((!isset($input['rating_id']))OR ( $input['rating_id'] == '')) {
            $id = (int) $this->rating->makeNewRating($input['member_id']);
            $input['rating_id'] = $id;
        }

        if ($input['description'] != '') {
            $input['description'] = strip_tags($input['description']);
        } elseif (($input['description'] == '') && ($input['content'] != '')) {
            $input['description'] = Str::words(strip_tags($input['content']), 10);
        } elseif (($input['description'] == '') && ($input['content'] == '') && ($input['title'] != '')) {
            $input['description'] = strip_tags($input['title']);
        }

        if ($input['keywords'] == '') {

            $expl_keywords = explode(' ', $input['description']);

            if (isset($expl_keywords[1])) {

                $keywords_string = '';
                foreach ($expl_keywords as $key => $value) {
                    $st = preg_replace("/[^a-zA-ZА-Яа-яёЁ0-9\s]/u", "", $value);
                    if ($st != '')
                        $keywords_string = $keywords_string . Str::lower($st) . ', ';
                }

                $keywords_string = substr($keywords_string, 0, -2);
            } else {
                $keywords_string = Str::lower($expl_keywords[0]);
            }

            $input['keywords'] = $keywords_string;
        }

        if ($input['content'] == '') {
            $input['content'] = $input['title'];
        }

        if ((!isset($input['url_thumb']))OR ( $input['url_thumb'] == '')) {
            $input['url_thumb'] = asset('css/noimage.jpg');
        }
        return $input;
    }

    public function publishAnswer($request) {
        $ret = $this->checkRestrictAnswerPublish();
        if (!$ret) {
            return ['error' => trans('messages.limit-publish')];
        }
        $data = Input::all();
        $title = trim($data['title']);
        $questionId = (int) ($data['questionId']);
        $voice = (int) $data['voice'];
        $camera = isset($data['camId']) && ($data['camId'] != '') ? (int) $data['camId'] : 0;
        if (isset($data['videoFilter'])) {
            $videoFilter = (int) $data['videoFilter'];
            $mobile = false;
        } else {
            $videoFilter = 0;
            $mobile = true;
        }

        $firstFiltres = ['normal', 'negate', 'blur'];

        if (isset($data['videoFilterMore'])) {
            $videoFilterMore = trim($data['videoFilterMore']);
            if (in_array($videoFilterMore, $firstFiltres)) {

                $videoFilter = array_search($videoFilterMore, $firstFiltres);
                $videoFilterMore = false;
            } else {
                $allowFiltres = ['bw0r', 'distort0r', 'invert0r', 'pixeliz0r', 'sobel',
                    'twolay0r', 'cartoon', 'baltan', 'nervous', 'scanline0r', 'threelay0r', 'luminance'/* ,'glow' */];
                if (!in_array($videoFilterMore, $allowFiltres)) {
                    $videoFilterMore = false;
                }
            }
        } else {
            $videoFilterMore = false;
        }

        $fileName = trim($data['video-filename']);
        $fileNameWithoutExt = Str::slug($title);

        $ext = \File::extension($fileName);
        $fileName = $fileNameWithoutExt . '.' . $ext;

        if (($title != '')AND ( ($voice >= 0)AND ( $voice <= 2))AND ( ($videoFilter >= 0)AND ( $videoFilter <= 2))AND ( $ext == 'webm')AND ( $questionId > 0)) {
            $file = Request::file('video-blob');
            $mime = $file->getMimeType();

            if (($mime == 'video/webm')OR ( $mime == 'application/octet-stream')OR ( $mime == 'application/ogg')OR ( $mime == 'video/3gpp')OR ( $mime == 'video/3gpp2')OR ( $mime == 'video/mp4')) {
                $ret = $this->member->makeUserDir($param = '/answers');
                $retTmpDir = $this->member->checkOrCreateTmpDir($param = '/answers');
                if ($ret) {
                    $pathFile = $ret . '/' . $fileName;
                    $pathDir = $ret;
                    if (!File::exists($pathFile)) {
                        $return = $file->move($pathDir, $fileName);
                    } else {
                        return ['error' => trans('messages.file-exist')];
                    }
                    if ($return) {

                        \Queue::push('\App\Commands\videoDecode', [
                            'path' => $pathFile,
                            'pathDir' => $pathDir,
                            'tmpDir' => $retTmpDir,
                            'title' => $title,
                            'filename' => $fileNameWithoutExt,
                            'member_id' => $this->member->getThisMember()[0]['id'],
                            'ext' => $ext,
                            'voice' => $voice,
                            'video' => $videoFilter,
                            'question_id' => $questionId,
                            'more' => $videoFilterMore,
                            'mobile' => $mobile,
                            'camera' => $camera
                        ]);

                        return ['success' => trans('messages.video-a-upload')];
                    } else {
                        return ['error' => trans('messages.wrong-data-file')];
                    }
                }
            } else {
                return ['error' => trans('messages.wrong-data-mime')];
            }
        } else {
            return ['error' => trans('messages.wrong-data-title-qid')];
        }
    }

    private function checkRestrictAnswerPublish() {
        $member = $this->member->getThisMember();

        if (isset($member[0]['id'])) {
            $flagRestrickt = $member[0]['restrict_flag'];
            if ($flagRestrickt == '1') {
                return true;
            }
            $memberId = $member[0]['id'];
            $memberAnswerRestrictDay = $member[0]['answers_restrict_day'];
            if ($memberAnswerRestrictDay == NULL) {
                $today = \Carbon\Carbon::now()->toDateString();
                $this->member->updateById($memberId, ['answers_restrict_day' => $today, 'answers_restrict' => $this->answersRestrictDay--]);
                return true;
            } else {

                $today = \Carbon\Carbon::now()->toDateString();
                ;
                $memberAnswerRestrictDay = new Carbon($member[0]['answers_restrict_day']);
                if ($today > $memberAnswerRestrictDay) {
                    $this->member->updateById($memberId, ['answers_restrict' => $this->questionsRestrictDay--, 'answers_restrict_day' => $today]);
                    return true;
                } else {
                    $count = (int) $member[0]['answers_restrict'];
                    if ($count > 0) {
                        $count--;
                    }

                    if ($count == 0) {
                        $this->member->updateById($memberId, ['answers_restrict' => $count, 'answers_restrict_day' => $today]);
                        return false;
                    } else {
                        $this->member->updateById($memberId, ['answers_restrict' => $count, 'answers_restrict_day' => $today]);
                        return true;
                    }
                }
            }
        } else {
            return false;
        }
    }

    public function showForApi($id, $page) {
        $limit = $this->answersPerVideoLimitAndroid;
        if ($page == 0) {

            $skip = 0;
        } else {
            $skip = $page * $limit;
        }

        $ret = $this->model
                ->where('question_id', $id)
                ->with('rating')
                ->take($this->answersPerVideoLimitAndroid)
                ->where('visible', 1)
                ->skip($skip)
                ->take($limit)
                ->orderBy('id', 'DESC')
                ->get();

        return ['answers' => $ret];
    }

    public function getLastAnswersIds($oldId) {
        return $this->model
                        ->where('id', '>', $oldId)
                        ->where('visible', 1)
                        ->select('id')
                        ->get()
                        ->toArray();
    }

    public function getLastAnswersIdsYour($oldId, $memberId) {
        return $this->model
                        ->where('id', '>', $oldId)
                        ->whereHas('question', function($query) use ($memberId) {
                            $query->where('member_id', $memberId);
                        })
                        ->where('visible', 1)
                        ->select('id')
                        ->get()
                        ->toArray();
    }

}
