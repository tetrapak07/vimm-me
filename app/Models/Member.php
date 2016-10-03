<?php namespace App\Models;

 /**
 * This is the Question model. 
 * It allow us to interact with the 'questions' table 
 * 
 * @package Models
 * @author Den
 */
use Illuminate\Database\Eloquent\Model;

class Member extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'members';

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
   * Defines the hasMany/belongsTo (one to many) relationship between Member and Question models
   *
   * @return mixed
   */
  public function questions() {
    return $this->hasMany('App\Models\Question');
  }
  
  /**
   * Defines the hasMany/belongsTo (one to many) relationship between Member and Answer models
   *
   * @return mixed
   */
  public function answers() {
    return $this->hasMany('App\Models\Answer');
  }
  
   /**
   * Defines the belongsToMany/belongsToMany (many to many) relationship between Member and Rating models
   *
   * @return mixed
   */
  public function ratings() {
    return $this->belongsToMany('App\Models\Rating', 'members_ratings', 'rating_id', 'member_id');
  }
 
}

