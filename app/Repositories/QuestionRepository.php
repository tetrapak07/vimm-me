<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Question as QuestionModel;
use App\Repositories\SettingRepository;
use Input;
use Request;
use File;
use App\Repositories\MemberRepository;
use App\Repositories\RatingRepository;
use Illuminate\Support\Str;
use App;
use Carbon\Carbon;

/**
 * Question Repository
 *
 * @package   Repositories
 * @author    Den
 */
class QuestionRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(QuestionModel $model, SettingRepository $setting, MemberRepository $member, RatingRepository $rating
    ) {

        $this->model = $model;
        $this->countPerPage = 10;
        $this->countPerPageFrontend = 3;
        $this->countPerPageFrontendAndroid = 5;
        $this->commentsPerVideoLimit = 3;
        $this->limitSummaryRating = '-10'; // Don't show content less this value
        $this->setting = $setting;
        $this->member = $member;
        $this->rating = $rating;
        $this->questionsRestrictDay = 3;
    }

    public function getAllQuestions() {
        $res = $this->model->all()->toArray();
        $resArray = [];
        foreach ($res as $value) {
            $resArray[$value['name']] = $value['value'];
        }
        $resObject = (object) $resArray;
        return $resObject;
    }

    public function getMaxQuestionId() {
        return $this->getMax();
    }

    public function getQuestionsToApi($page) {
        $limit = $this->countPerPageFrontendAndroid;
        if ($page == 0) {

            $skip = 0;
        } else {
            $skip = $page * $limit;
        }

        $ret = $this->model
                ->whereHas('member', function($query) {

                    $query->where('visible', '1');
                })
                ->whereHas('rating', function($query) {

                    $query->where('summary', '>=', $this->limitSummaryRating);
                })
                ->with(['answers' => function($query) {

                        $query->where('visible', '1')
                        ->whereHas('rating', function($query2) {
                                    $query2->where('summary', '>=', $this->limitSummaryRating);
                                })
                        ->whereHas('member', function($query2) {

                                    $query2->where('visible', '1');
                                })->orderBy('id', 'DESC');
                    },
                    'member' => function($query) {

                        $query->where('visible', '1');
                    },
                    'rating' => function($query) {
                        $query->where('summary', '>=', $this->limitSummaryRating);
                    }
                ])
                ->where('visible', 1)
                ->orderBy('id', 'DESC')
                ->skip($skip)
                ->take($limit)
                ->get()
                ->toArray();
        return ['questions' => $ret];
    }

    public function getAllVisibleQuestions() {
        return $this->model
                        ->where('visible', 1)
                        ->orderBy('id', 'DESC')
                        ->get();
    }

    public function getAllQ() {
        return $this->model
                        ->orderBy('id', 'DESC')
                        ->get();
    }

    public function getQuestionsPaginate($count = 10) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function getQuestionsWithAnswers() {
        return $this->model
                        ->whereHas('member', function($query) {

                            $query->where('visible', '1');
                        })
                        ->whereHas('rating', function($query) {

                            $query->where('summary', '>=', $this->limitSummaryRating);
                        })
                        ->with(['answers' => function($query) {
                                $query->where('visible', '1')
                                ->whereHas('rating', function($query2) {
                                            $query2->where('summary', '>=', $this->limitSummaryRating);
                                        })
                                ->whereHas('member', function($query2) {

                                            $query2->where('visible', '1');
                                        })->orderBy('id', 'DESC');
                            },
                            'member' => function($query) {

                                $query->where('visible', '1');
                            },
                            'rating' => function($query) {
                                $query->where('summary', '>=', $this->limitSummaryRating);
                            }
                        ])
                        ->where('visible', '1')
                        ->orderBy('id', 'DESC')
                        ->paginate($this->countPerPageFrontend);
    }

    public function getQuestionById($id) {
        return $this->getById($id);
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function getFilteredQuestionData($limit, $member, $rating, $sort = 'desc', $offset = 0) {
        $questions = $this->model
                ->whereHas('member', function($query) use ($member) {
                    if ($member != '') {
                        $query->where('id', $member);
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
                    'rating' => function($query) use ($rating) {
                        if ($rating != '') {
                            $query->where('id', $rating);
                        }
                    }])
                ->orderBy('id', $sort)
                ->take($limit)
                ->skip($offset)
                ->get();

        return ['questions' => $questions, 'limit' => $limit, 'memberSel' => $member, 'ratingSel' => $rating, 'sort' => $sort];
    }

    public function getQuestionBySlug($slug) {
        $settings = $this->setting->getAllSettings();
        $question = $this->model
                ->where('visible', 1)
                ->where('slug', $slug)
                ->first();
        if (!isset($question->id)) {
            App::abort(404);
        }

        $data = [
            'settings' => $settings,
            'question' => $question
        ];
        return $data;
    }

    public function publishQuestion($request) {
        $ret = $this->checkRestrictQuestionPublish();
        if (!$ret) {
            return ['error' => trans('messages.limit-publish')];
        }
        $data = Input::all();

        $title = trim($data['title']);

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

        if (($title != '')AND ( ($voice >= 0)AND ( $voice <= 2))AND ( ($videoFilter >= 0)AND ( $videoFilter <= 2))AND ( $ext == 'webm')) {

            if (true)
                $file = Request::file('video-blob');

            $mime = $file->getMimeType();

            if (($mime == 'video/webm')OR ( $mime == 'application/octet-stream')OR ( $mime == 'application/ogg')OR ( $mime == 'video/3gpp')OR ( $mime == 'video/3gpp2')OR ( $mime == 'video/mp4')) {

                $ret = $this->member->makeUserDir($param = '/questions');
                $retTmpDir = $this->member->checkOrCreateTmpDir($param = '/questions');
                if ($ret) {
                    if ($file->isValid()) {
                        $pathFile = $ret . '/' . $fileName;
                        $pathDir = $ret;
                        if (!File::exists($pathFile)) {
                            $return = $file->move($pathDir, $fileName);
                        } else {
                            return ['error' => 'File already exist!'];
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
                                'more' => $videoFilterMore,
                                'mobile' => $mobile,
                                'camera' => $camera
                            ]);

                            return ['success' => trans('messages.video-a-upload')];
                        } else {
                            return ['error' => trans('messages.wrong-data-file')];
                        }
                    }
                }
            } else {
                return ['error' => trans('messages.wrong-data-mime')];
            }
        } else {
            return ['error' => trans('messages.wrong-data-title-voice')];
        }
    }

    public function prepareForStoreQuestion($input) {
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

    private function checkRestrictQuestionPublish() {
        $member = $this->member->getThisMember();

        if (isset($member[0]['id'])) {
            $flagRestrickt = $member[0]['restrict_flag'];
            if ($flagRestrickt == '1') {
                return true;
            }
            $memberId = $member[0]['id'];
            $memberQuestionRestrictDay = $member[0]['questions_restrict_day'];
            if ($memberQuestionRestrictDay == NULL) {
                $today = \Carbon\Carbon::now()->toDateString();
                $this->member->updateById($memberId, ['questions_restrict_day' => $today, 'questions_restrict' => $this->questionsRestrictDay--]);
                return true;
            } else {

                $today = \Carbon\Carbon::now()->toDateString();
                ;
                $memberQuestionRestrictDay = new Carbon($member[0]['questions_restrict_day']);
                if ($today > $memberQuestionRestrictDay) {
                    $this->member->updateById($memberId, ['questions_restrict' => $this->questionsRestrictDay--, 'questions_restrict_day' => $today]);
                    return true;
                } else {
                    $count = (int) $member[0]['questions_restrict'];
                    if ($count > 0) {
                        $count--;
                    }
                    if ($count == 0) {
                        $this->member->updateById($memberId, ['questions_restrict' => $count, 'questions_restrict_day' => $today]);
                        return false;
                    } else {
                        $this->member->updateById($memberId, ['questions_restrict' => $count, 'questions_restrict_day' => $today]);
                        return true;
                    }
                }
            }
        } else {
            return false;
        }
    }

    public function getLastQuestionsIds($oldId) {
        return $this->model
                        ->where('id', '>', $oldId)
                        ->select('id')
                        ->where('visible', 1)
                        ->get()
                        ->toArray();
    }

}
