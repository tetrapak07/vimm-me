<?php namespace App\Repositories\Admin;

use App\Core\EloquentRepository;
use App\Models\Admin as AdminModel;
use AdminAuth;
use Hash;
use App\Repositories\SettingRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;
use File;

/**
 * Admin Repository
 *
 * Repository for custom methods of Admin model
 *
 * @package   Repositories
 * @author    Den
 */
class AdminRepository extends EloquentRepository {

    /**
     *
     * @var model
     */
    protected $model;

    public function __construct(AdminModel $model, SettingRepository $setting, QuestionRepository $question, AnswerRepository $answer
    ) {

        $this->model = $model;

        $this->question = $question;
        $this->answer = $answer;
    }

    /**
     * Change Admin Password
     *
     * @param AdminRequest $request change password post data
     * @return array
     */
    public function changePassword($request) {
        $input = \Request::all();
        $adminId = AdminAuth::user()->id;
        $admin = $this->getById($adminId);
        $hashedOldPassword = $admin->password;
        $oldPassword = $input['old_password'];
        if (Hash::check($oldPassword, $hashedOldPassword)) {
            $newPassword = bcrypt($input['password']);
            $admin->password = $newPassword;
            $admin->save();
            return ['message' => 'Your Password has been updated.'];
        } else {
            return ['error' => 'Old password does not match'];
        }
    }

    public function sitemapGen() {
        
    }

    public function sitemapGenCommand($adress) {
        set_time_limit(0);
        $domain = $adress;
        echo 'domain: ' . $domain;
        $timestamp = date('c', time());
        $filename = 'sitemap';
        $format = 'xml';
        $file = public_path() . DIRECTORY_SEPARATOR . $filename . '.' . $format;
        $contentXmlFirst = '<' . '?' . 'xml version="1.0" encoding="UTF-8"?>' . "\n";

        $questions = $this->question->getAllVisibleQuestions();
        $questionsCount = $questions->count();
        $answers = $this->answer->getAllVisibleAnswers();
        $answersCount = $answers->count();
        $countAll = $questionsCount + $answersCount;

        if ($countAll < 50000) {
            $size = 'small';
        } else {
            $size = 'big';
        }
        echo ' questions count: ' . $questionsCount . '; answers count: ' . $answersCount . '; all count: ' . $countAll . '; size: ' . $size;
        $content = '';
        $contentMain = '';

        if (true) {
            $contentXmlFirstRow = $contentXmlFirst . '<' . '?' . 'xml-stylesheet href="/vendor/sitemap/styles/xml.xsl" type="text/xsl"?>';
            $contentXmlFirst = '<' . '?' . 'xml-stylesheet href="/vendor/sitemap/styles/sitemapindex.xsl" type="text/xsl"?>';
            $countRows = 0;
            $countFilesThis = 0;
            $contentMainPart = '';
            $countFilesAll = floor($countAll / 50000);
            echo '; count files: ' . $countFilesAll;
            $contentXmlSecondRow = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $contentXmlEndRow = '</sitemapindex>';

            for ($i = 0; $i <= $countFilesAll; $i++) {
                $fileThisDel = public_path() . DIRECTORY_SEPARATOR . $filename . '-' . $i . '.' . $format;
                File::delete($fileThisDel);
                $contentMain = $contentMain . '<sitemap>' . "\n" . '<loc>' . $domain . '/sitemap-' . $i . '.xml</loc>' . "\n" . '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($timestamp)) . '</lastmod>' . "\n" . '</sitemap>' . "\n";
            }

            $fileThis = public_path() . DIRECTORY_SEPARATOR . $filename . '.' . $format;

            $contentXmlFirstRowPart = $contentXmlFirstRow . "\n";
            $contentXmlFirstRow = $contentXmlFirst . "\n";
            $contentXmlSecondRowPart = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";
            $contentXmlEndRowPart = '</urlset>';
            $contentIndexFirst = $contentXmlFirstRow . $contentXmlSecondRow . $contentMain . $contentXmlEndRow;
            File::delete($fileThis);
            File::put($fileThis, $contentIndexFirst);

            $fileThis = public_path() . DIRECTORY_SEPARATOR . $filename . '-' . $countFilesThis . '.' . $format;
            $contentPartFirst = $contentXmlFirstRowPart . $contentXmlSecondRowPart;

            File::put($fileThis, $contentPartFirst);

            foreach ($questions as $question) {

                if ($countRows > 49999) {
                    $contentPart = $contentXmlEndRowPart;
                    File::append($fileThis, $contentPart);
                    $countFilesThis++;
                    $countRows = 0;
                    $fileThis = public_path() . DIRECTORY_SEPARATOR . $filename . '-' . $countFilesThis . '.' . $format;
                    File::put($fileThis, $contentPartFirst);
                }
                $url = $domain . '/question/' . $question->slug;
                $contentMainPart = '<url>' . "\n" . '<loc>' . $url . '</loc>' . "\n" . '<priority>0.95</priority>' . "\n" . '<lastmod>' . $timestamp . '</lastmod>' . "\n" . '<changefreq>daily</changefreq>' . "\n" . '</url>' . "\n";
                $countRows++;
                File::append($fileThis, $contentMainPart);
            }


            foreach ($answers as $answer) {

                if ($countRows >= 49999) {
                    $contentPart = $contentXmlEndRowPart;
                    File::append($fileThis, $contentPart);
                    $countFilesThis++;
                    $countRows = 0;
                    $fileThis = public_path() . DIRECTORY_SEPARATOR . $filename . '-' . $countFilesThis . '.' . $format;
                    File::put($fileThis, $contentPartFirst);
                }
                $url = $domain . '/answer/' . $answer->slug;
                $contentMainPart = '<url>' . "\n" . '<loc>' . $url . '</loc>' . "\n" . '<priority>0.9</priority>' . "\n" . '<lastmod>' . $timestamp . '</lastmod>' . "\n" . '<changefreq>daily</changefreq>' . "\n" . '</url>' . "\n";
                $countRows++;
                File::append($fileThis, $contentMainPart);
            }



            $contentPart = $contentXmlEndRowPart;
            File::append($fileThis, $contentPart);
        }

        echo 'Your Sitemap was generated.';
    }

}
