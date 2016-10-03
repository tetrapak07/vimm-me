<?php namespace App\Repositories;

use App\Core\EloquentRepository;
use App\Repositories\SettingRepository;
use App\Repositories\PageRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;
use App\Repositories\MemberRepository;
use Cache;

/**
 * Welcome Repository
 * 
 * Repository for custom methods of index Page
 * 
 * @package   Repositories
 * @author    Den
 */
class WelcomeRepository extends EloquentRepository {

    /**
     *
     * @var page
     */
    protected $page;

    /**
     *
     * @var setting
     */
    protected $setting;

    /**
     *
     * @var question
     */
    protected $question;
    protected $memberId;

    public function __construct(
    PageRepository $page, QuestionRepository $question, AnswerRepository $answer, MemberRepository $member,
            SettingRepository $setting) {


        $this->page = $page;
        $this->setting = $setting;
        $this->question = $question;
        $this->answer = $answer;
        $this->member = $member;
    }

    public function getStartDataForApi($page) {
        $lastQuestionId = $this->question->getMaxQuestionId();
        $lastAnswerId = $this->answer->getMaxAnswerId();
        $member = $this->member->getThisMember();
        $memberId = $member[0]['id'];

        $lastYourAnswerId = $this->answer->getMaxAnswerIdWithMemberId($memberId);

        $this->member->saveUserCache($lastQuestionId, $lastAnswerId, $lastYourAnswerId);
        return $this->question->getQuestionsToApi($page);
    }

    public function checkNewsApi() {
        $this->memberId = $this->member->getThisMember()[0]['id'];
        $memberId = $this->memberId;
        $lastQuestionKey = $memberId . 'question';
        $lastAnswerKey = $memberId . 'answer';
        $lastYourAnswerKey = $memberId . 'answeryou';
        $lastQuestionId = $this->question->getMaxQuestionId();
        $lastAnswerId = $this->answer->getMaxAnswerId();

        $lastYourAnswerId = $this->answer->getMaxAnswerIdWithMemberId($memberId);
        if (Cache::has($lastQuestionKey)) {
            $lastQuestion = Cache::get($lastQuestionKey);
        } else {
            $lastQuestion = $lastQuestionId . '';
        }
        if (Cache::has($lastAnswerKey)) {
            $lastAnswer = Cache::get($lastAnswerKey);
        } else {
            $lastAnswer = $lastAnswerId . '';
        }
        if (Cache::has($lastYourAnswerKey)) {
            $lastYourAnswer = Cache::get($lastYourAnswerKey);
        }
        if (!isset($lastYourAnswer)) {
            $lastYourAnswer = $lastYourAnswerId . '';
        }

        if ($lastQuestionId > (int) $lastQuestion) {
            $questionsIds = $this->question->getLastQuestionsIds((int) $lastQuestion);
        } else {
            $questionsIds = [];
        }

        if ($lastAnswerId > (int) $lastAnswer) {
            $answersIds = $this->answer->getLastAnswersIds((int) $lastAnswer);
        } else {
            $answersIds = [];
        }

        if ($lastYourAnswerId > (int) $lastYourAnswer) {
            $answersIdsYour = $this->answer->getLastAnswersIdsYour((int) $lastYourAnswer, $memberId);
        } else {
            $answersIdsYour = [];
        }

        return ['questions' => $questionsIds, 'answers' => $answersIds, 'you' => $answersIdsYour];
    }

    public function getDataForIndexPage($page) {


        $settings = $this->setting->getAllSettings();


        $questions = $this->question->getQuestionsWithAnswers();


        $data = [
            'categoryTitle' => $settings->main_page_h2,
            'settings' => $settings,
            'questions' => $questions
        ];

        return $data;
    }

}
