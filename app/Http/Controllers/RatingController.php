<?php namespace App\Http\Controllers;

use App\Repositories\RatingRepository;
use Illuminate\Http\Request;

/**
 * Rating Controller
 * 
 * @package Http
 * @subpackage Controllers
 */
class RatingController extends Controller {

    /**
     * Rating Repository
     *
     * @var App\Repositories\RatingRepository
     */
    protected $rating;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RatingRepository $rating) {
        $this->middleware('checkMember');
        $this->middleware('setLoc');
        $this->rating = $rating;
    }

    /**
     * Change Rating.
     *
     * @return Response
     */
    public function ratingChange(Request $request) {
        return $this->rating->ratingChange($request);
    }

}
