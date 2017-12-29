<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
  /**
   * Fields that can be mass assigned.
   *
   * @var array
   */
    protected $fillable = [
      'pn',
      'product_type',
      'activity_type',
      'target',
      'account_id',
      'number',
      'nik',
      'status',
      'target_closing_date'
    ];

  /**
   * Fields that can be mass assigned.
   *
   * @var array
   */
    protected $hidden = ['created_at', 'updated_at'];


    public function activity()
    {
      return $this->hasMany('App\Models\Crm\MarketingActivity');
    }
}
