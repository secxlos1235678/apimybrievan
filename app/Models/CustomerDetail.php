<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Auth;
use File;

class CustomerDetail extends Model
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'customer_details';

    /**
     * Disabling timestamp feature.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'city_id', 'nik', 'birth_place_id', 'birth_date', 'address', 'citizenship_id', 'status', 'address_status', 'mother_name', 'emergency_contact', 'emergency_relation', 'identity', 'npwp', 'legal_document', 'salary_slip', 'bank_statement', 'family_card', 'marrital_certificate', 'diforce_certificate', 'job_type_id', 'job_id', 'company_name', 'job_field_id', 'position', 'work_duration', 'office_address', 'salary', 'other_salary', 'loan_installment', 'dependent_amount', 'couple_nik', 'couple_name', 'couple_birth_place_id', 'couple_birth_date', 'couple_identity', 'couple_salary', 'couple_other_salary', 'couple_loan_installment', 'emergency_name', 'is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'user_id'
    ];

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getNpwpAttribute( $value )
    {
        if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
            $image = url( 'uploads/users/' . $this->user_id . '/' . $value );
        } else {
            $image = url( 'img/noimage.jpg' );
        }
        return $image;
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getIdentityAttribute( $value )
    {
        if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
            $image = url( 'uploads/users/' . $this->user_id . '/' . $value );
        } else {
            $image = url( 'img/noimage.jpg' );
        }
        return $image;
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getCoupleIdentityAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getLegalDocumentAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getSalarySlipAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getBankStatementAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getFamilyCardAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getMarritalCertificateAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getDiforceCertificateAttribute( $value )
    {
        if( ! empty( $value ) ) {
            if( File::exists( 'uploads/users/' . $this->user_id . '/' . $value ) ) {
                return url( 'uploads/users/' . $this->user_id . '/' . $value );
            }
        }
        return url( 'img/noimage.jpg' );
    }

    /**
     * Get user avatar image url.
     *
     * @return string
     */
    public function getStatusAttribute( $value )
    {
        if( $value == 0 ) {
            return 'Tidak menikah';
        } else if( $value == 1 ) {
            return 'Menikah';
        } else if( $value == 2 ) {
            return 'Janda';
        } else if( $value == 3 ) {
            return 'Duda';
        }

        return null;
    }

    /**
     * Get user String Address_status.
     * @author Akse
     * @return string
     */
    public function getStatusAddressAttribute( $value )
    {
        if( $value == 0 ) {
            return 'Milik Sendiri';
        } else if( $value == 1 ) {
            return 'Milik Orang Tua/Mertua atau Rumah Dinas';
        } else if( $value == 3 ) {
            return 'Tinggal di Rumah Kontrakan';
        }

        return null;
    }

    /**
     * Get user citizenship information.
     *
     * @return string
     */
    public function getCitizenshipAttribute( $value )
    {
        $citizenship_search = \Asmx::setEndpoint( 'GetNegara' )->setQuery( [
            'limit' => 1,
            'search' => $this->citizenship_id,
        ] )->post();
        if( $citizenship_search[ 'code' ] == 200 && ! empty( $citizenship_search[ 'data' ] ) ) {
            return $citizenship_search[ 'contents' ][ 'data' ][ 0 ][ 'desc2' ];
        }
        return null;
    }

    /**
     * Set customer npwp image.
     *
     * @return void
     */
    public function setNpwpAttribute( $image )
    {
        if ($this->user_id) {
            $id = $this->user_id;
        }else{
            $id = user_info('id');
        }
        $path = public_path( 'uploads/users/' . $id . '/' );
        if ( ! empty( $this->attributes[ 'npwp' ] ) ) {
            File::delete( $path . $this->attributes[ 'npwp' ] );
        }

        if (!$image->getClientOriginalExtension()) {
            if ($image->getMimeType() == 'image/jpg') {
                $extension = 'jpg';
            }elseif ($image->getMimeType() == 'image/jpeg') {
                $extension = 'jpeg';
            }else{
                $extension = 'png';
            }
        }else{
            $extension = $image->getClientOriginalExtension();
        }

        $filename = $id . '-npwp.' . $extension;
        $image->move( $path, $filename );
        $this->attributes[ 'npwp' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setIdentityAttribute( $image )
    {
        if ($this->user_id) {
            $id = $this->user_id;
        }else{
            $id = user_info('id');
        }

        $path = public_path( 'uploads/users/' . $id . '/' );
        if ( ! empty( $this->attributes[ 'identity' ] ) ) {
            File::delete( $path . $this->attributes[ 'identity' ] );
        }

        if (!$image->getClientOriginalExtension()) {
            if ($image->getMimeType() == 'image/jpg') {
                $extension = 'jpg';
            }elseif ($image->getMimeType() == 'image/jpeg') {
                $extension = 'jpeg';
            }else{
                $extension = 'png';
            }
        }else{
            $extension = $image->getClientOriginalExtension();
        }

        $filename = $id . '-identity.' . $extension;
        $image->move( $path, $filename );
        $this->attributes[ 'identity' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setCoupleIdentityAttribute( $image )
    {
        if ($this->user_id) {
            $id = $this->user_id;
        }else{
            $id = user_info('id');
        }

        $path = public_path( 'uploads/users/' . $id . '/' );
        if ( ! empty( $this->attributes[ 'couple_identity' ] ) ) {
            File::delete( $path . $this->attributes[ 'couple_identity' ] );
        }

        if (!$image->getClientOriginalExtension()) {
            if ($image->getMimeType() == 'image/jpg') {
                $extension = 'jpg';
            }elseif ($image->getMimeType() == 'image/jpeg') {
                $extension = 'jpeg';
            }else{
                $extension = 'png';
            }
        }else{
            $extension = $image->getClientOriginalExtension();
        }

        $filename = $id . '-couple_identity.' . $extension;
        $image->move( $path, $filename );
        $this->attributes[ 'couple_identity' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setLegalDocumentAttribute( $image )
    {
        if ($this->user_id) {
            $id = $this->user_id;
        }else{
            $id = user_info('id');
        }

        $path = public_path( 'uploads/users/' . $id . '/' );
        if ( ! empty( $this->attributes[ 'legal_document' ] ) ) {
            File::delete( $path . $this->attributes[ 'legal_document' ] );
        }

        if (!$image->getClientOriginalExtension()) {
            if ($image->getMimeType() == 'image/jpg') {
                $extension = 'jpg';
            }elseif ($image->getMimeType() == 'image/jpeg') {
                $extension = 'jpeg';
            }else{
                $extension = 'png';
            }
        }else{
            $extension = $image->getClientOriginalExtension();
        }

        $filename = $id . '-legal_document.' . $extension;
        $image->move( $path, $filename );
        $this->attributes[ 'legal_document' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setSalarySlipAttribute( $image )
    {
        $path = public_path( 'uploads/users/' . $this->user_id . '/' );
        if ( ! empty( $this->attributes[ 'salary_slip' ] ) ) {
            File::delete( $path . $this->attributes[ 'salary_slip' ] );
        }

        $filename = $this->user_id . '-salary_slip.' . $image->getClientOriginalExtension();
        $image->move( $path, $filename );
        $this->attributes[ 'salary_slip' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setBankStatementAttribute( $image )
    {
        $path = public_path( 'uploads/users/' . $this->user_id . '/' );
        if ( ! empty( $this->attributes[ 'bank_statement' ] ) ) {
            File::delete( $path . $this->attributes[ 'bank_statement' ] );
        }

        $filename = $this->user_id . '-bank_statement.' . $image->getClientOriginalExtension();
        $image->move( $path, $filename );
        $this->attributes[ 'bank_statement' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setFamilyCardAttribute( $image )
    {
        $path = public_path( 'uploads/users/' . $this->user_id . '/' );
        if ( ! empty( $this->attributes[ 'family_card' ] ) ) {
            File::delete( $path . $this->attributes[ 'family_card' ] );
        }

        $filename = $this->user_id . '-family_card.' . $image->getClientOriginalExtension();
        $image->move( $path, $filename );
        $this->attributes[ 'family_card' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setMarritalCertificateAttribute( $image )
    {
        $path = public_path( 'uploads/users/' . $this->user_id . '/' );
        if ( ! empty( $this->attributes[ 'marrital_certificate' ] ) ) {
            File::delete( $path . $this->attributes[ 'marrital_certificate' ] );
        }

        $filename = $this->user_id . '-marrital_certificate.' . $image->getClientOriginalExtension();
        $image->move( $path, $filename );
        $this->attributes[ 'marrital_certificate' ] = $filename;
    }

    /**
     * Set customer identity image.
     *
     * @return void
     */
    public function setDiforceCertificateAttribute( $image )
    {
        $path = public_path( 'uploads/users/' . $this->user_id . '/' );
        if ( ! empty( $this->attributes[ 'diforce_certificate' ] ) ) {
            File::delete( $path . $this->attributes[ 'diforce_certificate' ] );
        }

        $filename = $this->user_id . '-diforce_certificate.' . $image->getClientOriginalExtension();
        $image->move( $path, $filename );
        $this->attributes[ 'diforce_certificate' ] = $filename;
    }

    /**
     * The directories belongs to broadcasts.
     *
     * @return     \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function city()
    {
        return $this->belongsTo( City::class, 'city_id' );
    }

    /**
     * The directories belongs to broadcasts.
     *
     * @return     \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function birth_place_city()
    {
        return $this->belongsTo( City::class, 'birth_place_id' );
    }

    /**
     * The directories belongs to broadcasts.
     *
     * @return     \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function couple_birth_place_city()
    {
        return $this->belongsTo( City::class, 'couple_birth_place_id' );
    }
}
