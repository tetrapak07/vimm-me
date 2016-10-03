<?php namespace App\Models;

 /**
 * This is the Question model. 
 * It allow us to interact with the 'questions' table 
 * 
 * @package Models
 * @author Den
 */
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'answers';

    /**
     * The attributes that are mass not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Defines the belongsTo/hasMany (one to many) relationship between Answer and Member models
     *
     * @return mixed
     */
    public function member() {
        return $this->belongsTo('App\Models\Member');
    }

    /**
     * Defines the belongsTo/hasMany (one to many) relationship between Answer and Question models
     *
     * @return mixed
     */
    public function question() {
        return $this->belongsTo('App\Models\Question');
    }

    /**
     * Defines the belongsTo/hasOne(one to one) relationship between Answer and Rating models
     *
     * @return mixed
     */
    public function rating() {
        return $this->belongsTo('App\Models\Rating');
    }

}
