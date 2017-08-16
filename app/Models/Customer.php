<?php

namespace App\Models;

use App\Events\Customer\CustomerRegistered;
use App\Models\CustomerDetail;
use App\Models\User;

use Sentinel;

class Customer extends User
{
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [ 'personal', 'work', 'financial', 'contact', 'other', 'schedule' ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [ 'personal', 'work', 'financial', 'contact', 'other', 'schedule' ];

    /**
     * Get personal information of customer.
     *
     * @return bool
     */
    public function getPersonalAttribute()
    {
        $detail = $this->detail;
        return [
            'nik' => $detail->nik,
            'name' => $this->fullname,
            'birth_place' => $detail->birth_place,
            'birth_date' => $detail->birth_date,
            'address' => $detail->address,
            'gender' => $this->gender,
            'citizenship' => $detail->citizenship,
            'status' => $detail->status,
            'address_status' => $detail->address_status,
            'email' => $this->email,
            'mother_name' => $detail->mother_name
        ];
    }

    /**
     * Get work information of customer.
     *
     * @return bool
     */
    public function getWorkAttribute()
    {
        $detail = $this->detail;
        return [
            'type' => $detail->work_type,
            'work' => $detail->work,
            'company_name' => $detail->company_name,
            'work_field' => $detail->work_field,
            'position' => $detail->position,
            'work_duration' => $detail->work_duration,
            'office_address' => $detail->office_address
        ];
    }

    /**
     * Get financial information of customer.
     *
     * @return bool
     */
    public function getFinancialAttribute()
    {
        $detail = $this->detail;
        return [
            'salary' => $detail->salary,
            'other_salary' => $detail->other_salary,
            'loan_installment' => $detail->loan_installment,
            'dependent_amount' => $detail->dependent_amount
        ];
    }

    /**
     * Get contact information of customer.
     *
     * @return bool
     */
    public function getContactAttribute()
    {
        $detail = $this->detail;
        return [
            'phone' => $this->phone,
            'mobile_phone' => $this->mobile_phone,
            'emergency_contact' => $detail->emergency_contact,
            'emergency_relation' => $detail->emergency_relation
        ];
    }

    /**
     * Get other information of customer.
     *
     * @return bool
     */
    public function getOtherAttribute()
    {
        $detail = $this->detail;
        return [
            'identity' => $detail->identity,
            'npwp' => $detail->npwp,
            'image' => $this->image
        ];
    }

    /**
     * Get other information of customer.
     *
     * @return bool
     */
    public function getScheduleAttribute()
    {
        $schedules = [];
        foreach ( $this->eforms as $key => $eform ) {
            $schedules[] = [
                'date' => $eform->appointment_date,
                'ao_name' => $eform->ao->fullname,
                'branch' => $eform->branch,
                'agenda' => ''
            ];
        }
        return $schedules;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function create( $data ) {
        $password = str_random( 8 );
        $separate_array_keys = array_flip( $this->fillable );
        $user_data = array_intersect_key( $data, $separate_array_keys ) + [ 'password' => $password ];
        $user = Sentinel::registerAndActivate( $user_data );
        $role = Sentinel::findRoleBySlug( 'customer' );
        $role->users()->attach( $user );
        $customer_data = array_diff_key( $data, $separate_array_keys ) + [ 'user_id' => $user->id ];
        CustomerDetail::create( $customer_data );
        event( new CustomerRegistered( $this, '$password' ) );

        return static::find( $user->id );
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update( array $attributes = [], array $options = [] )
    {
        $separate_array_keys = array_flip( $this->fillable );
        $user_data = array_intersect_key( $attributes, $separate_array_keys );
        parent::update( $user_data );
        $customer_data = array_diff_key( $attributes, $separate_array_keys );
        $this->detail()->update( $customer_data );

        return true;
    }



    /**************************************************************************************
     *
     * Relationship functions
     *
     **************************************************************************************/

    /**
     * The directories belongs to broadcasts.
     *
     * @return     \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function detail()
    {
        return $this->hasOne( CustomerDetail::class, 'user_id' );
    }

    /**
     * The directories belongs to broadcasts.
     *
     * @return     \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eforms()
    {
        return $this->hasMany( EForm::class, 'user_id' );
    }
}