<?php
/**
 * This file is part of the Laravel Auditing package.
 *
 * @author     Antério Vieira <anteriovieira@gmail.com>
 * @author     Quetzy Garcia  <quetzyg@altek.org>
 * @author     Raphael França <raphaelfrancabsb@gmail.com>
 * @copyright  2015-2017
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Audit as AuditTrait;
use OwenIt\Auditing\Contracts\Audit as AuditContract;
use Illuminate\Http\Request;
use DB;

class Audit extends Model implements AuditContract
{
    use AuditTrait;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetLists($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_pengajuankredit')
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by field search ref_number.
                *
                * @param $request->search
                * @return \Illuminate\Database\Eloquent\Builder
                */ 

                 // $eform = 'app\\models\\eform';
                  
                  if ($request->has('search')){
                        $auditrail->where(\DB::raw('LOWER(ref_number)'), 'like', '%'.strtolower($request->input('search')).'%');
                       // $auditrail->where('auditable_type', $eform);
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                   //$eform = 'app\\models\\eform';

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       // $auditrail->where('auditable_type', $eform);
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    //$eform = 'app\\models\\eform';

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                        //$auditrail->where('auditable_type', $eform);
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
                    //$eform = 'app\\models\\eform';

                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
                        //$auditrail->where('auditable_type', $eform);
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by ref_number.
                *
                * @param $request->ref_number
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
                    

                  if ($request->has('ref_number')){
                        $auditrail->where(\DB::raw('LOWER(ref_number)'), 'like', '%'.strtolower($request->input('ref_number')).'%');
                        //$auditrail->where('auditable_type', $eform);
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Kanwil.
                *
                * @param $request->region_id
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
                    

                  if ($request->has('region_id')){
                        $auditrail->where(\DB::raw('LOWER(region_id)'), 'like', '%'.strtolower($request->input('region_id')).'%');
                   
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Kantor Cabang.
                *
                * @param $request->branch_id
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
                    

                  if ($request->has('branch_id')){
                        $auditrail->where(\DB::raw('LOWER(branch_id)'), 'like', '%'.strtolower($request->input('branch_id')).'%');
                        
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Pengajuan Kredit
                */
                 $eform = 'app\\models\\eform';
                 $data_action = ['pengajuan kredit', 'tambah leads', 'pengajuan kredit via ao', 'eform tambah leads via ao', 'disposisi kredit', 'verifikasi data nasabah'];
                 $appointment = 'app\\models\\appointment';
                 $propertyItem = 'app\\models\\propertyitem';
                 $auditrail->whereNotNull('username');
                 //$auditrail->where('auditable_type', '=', $eform);
                 //$auditrail->where(\DB::raw('LOWER(old_values)'), '!=', '[]');
                 $auditrail->where(\DB::raw('LOWER(new_values)'), '!=', '[]');
                 $auditrail->whereIn(DB::raw('lower(modul_name)'), $data_action);
                 $auditrail->where('auditable_type', '!=', $appointment);
                 $auditrail->where('auditable_type', '!=', $propertyItem);
                 //$auditrail->where(\DB::raw('LOWER(old_values)'), 'not like', '[]');
                 //$auditrail->where(\DB::raw('LOWER(new_values)'), 'not like', '[]');

                 // $auditrail->Orwhere(DB::raw('lower(modul_name)'), 'like', '%peng%');
                 // $auditrail->Orwhere(DB::raw('lower(modul_name)'), 'like', '%veri%');
                 // $auditrail->Orwhere(DB::raw('lower(modul_name)'), 'like', '%lkn%');
                 // $auditrail->Orwhere(DB::raw('lower(modul_name)'), 'like', '%leads%');
                 // $auditrail->Orwhere(DB::raw('lower(modul_name)'), 'like', '%si kredit%');
                 // $auditrail->Orwhere(DB::raw('lower(modul_name)'), 'not like', '%collateral%');
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            
    }

    /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsAppointment($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_type_one')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                   $appointment = 'app\models\appointment';

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                        $auditrail->where('auditable_type', $appointment);
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    $appointment = 'app\models\appointment';

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                        $auditrail->where('auditable_type', $appointment);
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
                    $appointment = 'app\models\appointment';

                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
                        $auditrail->where('auditable_type', $appointment);
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by ref_number.
                *
                * @param $request->ref_number
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
                     $appointment = 'app\models\appointment';

                  if ($request->has('ref_number')){
                        $auditrail->where(\DB::raw('LOWER(ref_number)'), 'like', '%'.strtolower($request->input('ref_number')).'%');
                        $auditrail->where('auditable_type', $appointment);
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Penjadwalan
                */
                 $appointment = 'app\models\appointment';

                 $auditrail->where('auditable_type', $appointment);
               
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            
    }
    /*
     save data action dan long lat before save
    */
     public static   function boot(  )
    {
        parent::boot();
        self::created(function($model){
           $request = request();
           $extraParams = array(
                'longitude' => number_format($request->header('long', env('DEF_LONG', '106.81350')), 5)
                , 'latitude' => number_format($request->header('lat', env('DEF_LAT', '-6.21670')), 5)
            );
            \Log::info("-------boot audit -------------");
            \Log::info($model->auditable_type);
           $model->extra_params =  json_encode($extraParams);
           $model->action = $request->header('auditaction', 'Undefined Action');
           $model->save();
        });   
    }

    /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsAdmindev($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_new_admin_dev')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->company_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('company_name')){
                        $auditrail->where(\DB::raw('LOWER(company_name)'), 'like', '%'.strtolower($request->input('company_name')).'%');
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Admin Developer
                */

                 $slug = 'developer';
                 $action = ['undefined action','login','logout'];
                 // $auditrail->where('role', $slug);
                $model_type = 'app\\models\\audit';
                $auditrail->where('auditable_type', '!=', $model_type);
                //$auditrail->where(\DB::raw('LOWER(old_values)'), 'not like', '[]');
                $auditrail->where(\DB::raw('LOWER(new_values)'), 'not like', '[]');
                $auditrail->whereIn(\DB::raw('LOWER(modul_name)'), ['tambah admin dev','banned admin dev','unbanned admin dev','edit proyek','tambah agen','ubah admin dev','unbanned agen','banned agen','edit tipe property','tambah tipe property','tambah proyek','edit agen','tambah unit property']);
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            }

    /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsLogin($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['created_at', 'desc'];

        return $query
                ->from('auditrail_admin_developer')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->company_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('company_name')){
                        $auditrail->where(\DB::raw('LOWER(company_name)'), 'like', '%'.strtolower($request->input('company_name')).'%');
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Login
                */

                $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%log%');
                $auditrail->whereNotNull(\DB::raw('LOWER(username)'));
               
                })
                ->orderBy('created_at', 'desc');
            }

    /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsAgenDev($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_admin_developer')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->company_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('company_name')){
                        $auditrail->where(\DB::raw('LOWER(company_name)'), 'like', '%'.strtolower($request->input('company_name')).'%');
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Login
                */

                 $slug = 'developer-sales';
                 $action = 'undefined action';
                 $auditrail->where('role', $slug);
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), '!=', $action);
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), '!=', 'login');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), '!=', 'logout');
               
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            }

            /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsEdit($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_profile_edit')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->company_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('company_name')){
                        $auditrail->where(\DB::raw('LOWER(company_name)'), 'like', '%'.strtolower($request->input('company_name')).'%');
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Login
                */

                 $user = 'app\models\user';

                // $auditrail->where('auditable_type', $user);
                 $auditrail->whereNotNull(\DB::raw('LOWER(username)'));
                 $auditrail->where(\DB::raw('LOWER(old_values)'), 'not like', '[]');
                 $auditrail->where(\DB::raw('LOWER(new_values)'), 'not like', '[]');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%p%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%u%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%pengajuan%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%tambah unit property%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%agunan%');
               
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            }

            /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsCollateral($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_collaterals')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->developer
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('developer')){
                        $auditrail->where(\DB::raw('LOWER(developer)'), strtolower($request->input('developer')));
            
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->staff_penilai
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('staff_penilai')){
                        $auditrail->where(\DB::raw('LOWER(staff_penilai)'), 'like', '%'.strtolower($request->input('staff_penilai')).'%');
                  
                    }
                })
                 ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Region id.
                *
                * @param $request->region_id
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('region_id')){
                        $auditrail->where(\DB::raw('LOWER(region_id)'), 'like', '%'.strtolower($request->input('region_id')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by region name.
                *
                * @param $request->region_name
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('region_name')){
                        $auditrail->where(\DB::raw('LOWER(region_name)'), 'like', '%'.strtolower($request->input('region_name')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by manager name.
                *
                * @param $request->manager_name
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('manager_name')){
                        $auditrail->where(\DB::raw('LOWER(manager_name)'), 'like', '%'.strtolower($request->input('manager_name')).'%');
                  
                    }
                }) 
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Login
                */
                           
               $model_type = ['app\models\collateral','app\models\otsdoc'];
               $auditrail->wherein('auditable_type', $model_type);
               $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%tambah proyek%');
               $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%undefined%');
               $auditrail->Orwhere(\DB::raw('LOWER(modul_name)'), 'like', '%ots%');

               
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            }

            /**
     * Scope a query to get lists of roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsProperty($query, Request $request)
    {

        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];

        return $query
                ->from('auditrail_property')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Proyek.
                *
                * @param $request->project_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('project_name')){
                        $auditrail->where(\DB::raw('LOWER(project_name)'), 'like', '%'.strtolower($request->input('project_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->company_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('company_name')){
                        $auditrail->where(\DB::raw('LOWER(company_name)'), 'like', '%'.strtolower($request->input('company_name')).'%');
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                  if ($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for Auditrail Login
                */
                 $auditrail->where(\DB::raw('LOWER(auditable_type)'), 'like', '%property%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%undefined action%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%pengajuan%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), 'not like', '%col%');
               
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
            }

    public function getuser(Request $request)
    {
        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];
        $useractivity = \DB::table('auditrail_admin_developer')
                            ->select('user_id', 'username', 'ip_address')
                            ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                            ->whereNotNull('username')
                            ->groupBy('user_id', 'username', 'ip_address');
                            //->orderBy($sort[0], $sort[1]);              
                            
        return $useractivity;
    }

        /**
     * Scope a query to get list detail activity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetListsDetailActivity($query, Request $request, $id)
    {


        $sort = $request->input('sort') ? explode('|', $request->input('sort')) : ['id', 'asc'];


        return $query
                ->from('auditrail_admin_developer')
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by tanggal aksi.
                *
                * @param $request->created_at
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if ($request->has('created_at')){
                        $auditrail->where(\DB::raw('DATE(created_at)'), $request->input('created_at'));
                       
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama User.
                *
                * @param $request->username
                * @return \Illuminate\Database\Eloquent\Builder
                */

                    if($request->has('username')){
                        $auditrail->where(\DB::raw('LOWER(username)'), 'like', '%'.strtolower($request->input('username')).'%');
                  
                    }
                })
                ->where(function ($auditrail) use ($request) {
               /**
                * This query for search by Nama Modul.
                *
                * @param $request->modul_name
                * @return \Illuminate\Database\Eloquent\Builder
                */
               
                    if($request->has('modul_name')){
                        $auditrail->where(\DB::raw('LOWER(modul_name)'), 'like', '%'.strtolower($request->input('modul_name')).'%');
      
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query){
                /**
                * This query for search by Nama Perusahaan Mitra.
                *
                * @param $request->company_name
                * @return \Illuminate\Database\Eloquent\Builder
                */ 
              
                if ($request->has('user_id')){
                        $auditrail->where('user_id',$request->input('user_id'));
            
                    }
                })
                ->where(function ($auditrail) use (&$request, &$query,&$id){
                /**
                * This query for Auditrail Admin Developer
                */

                  $action = 'undefined action';
                // $auditrail->where(\DB::raw('LOWER(old_values)'), 'not like', '[]');
                // $auditrail->where(\DB::raw('LOWER(new_values)'), 'not like', '[]');
                 // $auditrail->where(\DB::raw('LOWER(modul_name)'), '!=', $action);
                 // $auditrail->where('user_id', '=', $id);
                 // $auditrail->where(\DB::raw('LOWER(old_values)'), 'not like', '[]');
                 // $auditrail->Orwhere(\DB::raw('LOWER(modul_name)'), 'like', '%log%');
                 $auditrail->where(\DB::raw('LOWER(modul_name)'), '!=', $action);
                 $auditrail->where('user_id', '=', $id);
               
                })
                // ->orderBy($sort[0], $sort[1]);
                ->orderBy('created_at', 'desc');
  }
}
