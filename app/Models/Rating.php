<?php namespace App\Models;

 /**
 * This is the Question Rating model. 
 * It allow us to interact with the 'settings' table 
 * 
 * @package Models
 * @author Den
 */
use Illuminate\Database\Eloquent\Model;

class Rating extends Model {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'ratings';

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
   * Defines the belongsToMany/belongsToMany (many to many) relationship between Rating and Member models
   *
   * @return mixed
   */
  public function members() {
    return $this->belongsToMany('App\Models\Member', 'members_ratings', 'member_id', 'rating_id');
  }

 
}
