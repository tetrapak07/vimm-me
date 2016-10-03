<?php namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminQuestionRatingRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        $validation = [

            'question_id' => 'required|integer',
            'member_id' => 'required|integer',
        ];

        $page = \Input::get('page');

        if ($page != '') {
            $validation['page'] = 'integer';
        }

        return $validation;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

}
