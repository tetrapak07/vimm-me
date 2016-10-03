<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Models\Rating as RatingModel;
use Input;
use App\Repositories\MemberRepository;

/**
 * Rating Repository
 * 
 * @package   Repositories
 * @author    Den
 */
class RatingRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(RatingModel $model, MemberRepository $member
    ) {

        $this->model = $model;
        $this->member = $member;

        $this->countPerPage = 10;
    }

    public function getRatings() {
        return $this->getAll();
    }

    public function makeNewRating($memberId) {
        return $this->save(['rating_plus' => 0])->id;
    }

    public function getRatingsPaginate($count = 10) {
        $count = $this->countPerPage;
        return $this->getAllPaginated($count);
    }

    public function pageCount() {
        return ceil($this->model->count() / $this->countPerPage);
    }

    public function getRatingById($id) {
        return $this->getById($id);
    }

    public function getFilteredRatingData($limit, $member, $sort = 'desc', $offset = 0) {
        $ratings = $this->model
                ->orderBy('id', $sort)
                ->take($limit)
                ->skip($offset)
                ->get();

        return ['ratings' => $ratings, 'limit' => $limit, 'sort' => $sort];
    }

    public function ratingChange($request) {

        $data = Input::all();
        $ratingId = (int) $data['rating_id'];
        $action = trim($data['action']);

        if (($ratingId !== 0) && (($action == 'minus')OR ( $action == 'plus'))) {

            $thisMemberArray = $this->member->getThisMember();

            if (isset($thisMemberArray[0]['ip'])) {

                $thisMemberId = $thisMemberArray[0]['id'];
                $ret = $this->changeRatingAction($thisMemberId, $action, $ratingId);

                if ($ret) {

                    return ['newRating' => $ret, 'success' => trans('messages.thank-voting')];
                } else {

                    return ['error' => trans('messages.already-voted')];
                }
            } else {

                return ['error' => trans('messages.not-member')];
            }
        } else {

            return ['error' => trans('messages.wrong-data')];
        }
    }

    private function changeRatingAction($memberId, $action, $ratingId) {

        $existRating = $this->model
                ->join('members_ratings', 'ratings.id', '=', 'members_ratings.rating_id')
                ->where('member_id', $memberId)
                ->where('rating_id', $ratingId)
                ->get()
                ->first();

        if (isset($existRating) && ($existRating->exists == '1')) {
            return false;
        } else {
            $rating = $this->model->where('id', $ratingId)->get()->first();
            $ratingOldPlus = $rating->rating_plus;
            $ratingOldMinus = $rating->rating_minus;

            if ($action == 'plus') {
                $ratingNewPlus = $ratingOldPlus + 1;
                $ratingNewSumm = $ratingNewPlus - $ratingOldMinus;

                \DB::table('members_ratings')->insert(['member_id' => $memberId, 'rating_id' => $ratingId]);
                $ret = $rating->update(['rating_plus' => $ratingNewPlus, 'summary' => $ratingNewSumm]);
                return $ratingNewPlus;
            } elseif ($action == 'minus') {
                $ratingNewMinus = $ratingOldMinus + 1;
                $ratingNewSumm = $ratingOldPlus - $ratingNewMinus;

                \DB::table('members_ratings')->insert(['member_id' => $memberId, 'rating_id' => $ratingId]);
                $ret = $rating->update(['rating_minus' => $ratingNewMinus, 'summary' => $ratingNewSumm]);
                return $ratingNewMinus;
            }
        }
    }

    private function sumRating($action, $ratingId) {
        if ($action == 'plus') {
            return $this->model
                            ->where('id', $ratingId)
                            ->where('rating_plus', '=', '1')
                            ->get()
                            ->count();
        } elseif ($action == 'plus') {
            return $this->model
                            ->where('id', $ratingId)
                            ->where('rating_minus', '=', '1')
                            ->get()
                            ->count();
        }
    }

    public function getRating($contentId, $type) {

        return $this->model
                        ->where('content_id', $contentId)
                        ->where('content', $type)
                        ->get();
    }

}
