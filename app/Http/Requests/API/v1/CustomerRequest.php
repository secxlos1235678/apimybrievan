<?php

namespace App\Http\Requests\API\v1;

use App\Http\Requests\BaseRequest;

class CustomerRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ( strtolower( $this->method() ) ) {
            case 'post':
                return [
                    'nik' => 'required|numeric|digits:16|unique:customer_details,nik',
                    'first_name' => 'required',
                    'last_name' => '',
                    'email' => 'required|email|unique:users,email',
                    'mobile_phone' => 'required|string|regex:/^[0-9]+$/|min:9|max:16',
                    'status' => 'required|in:0,1,2',
                    'mother_name' => 'required',
                    'birth_place_id' => 'required|numeric|exists:cities,id',
                    'birth_date' => 'required|date',
                    'identity' => 'required|image|mimes:jpg,jpeg,png',
                    'couple_nik' => 'required_if:status,1|numeric|digits:16',
                    'couple_name' => 'required_if:status,1',
                    'couple_birth_place_id' => 'required_if:status,1',
                    'couple_birth_date' => 'required_if:status,1|date',
                    'couple_identity' => 'required_if:status,1|image'
                ];
                break;
            
            case 'put':
                if( $this->segment( 6 ) == 'verify' ) {
                    return [

                        'form_id'=>'',
                        'birth_place_id'=>'',
                        'birth_date'=>'',
                        'city_id'=>'',
                        'status'=>'',
                        'address_status'=>'',
                        'citizenship_id'=>'',
                        'email'=>'',
                        'address'=>'',
                        'mother_name'=>'',
                        'phone'=>'',
                        'mobile_phone'=>'',
                        'couple_nik'=>'',
                        'couple_name'=>'',
                        'couple_birth_place_id'=>'',
                        'couple_birth_date'=>'',
                        'job_field_id'=>'',
                        'job_type_id'=>'',
                        'job_id'=>'',
                        'company_name'=>'',
                        'position'=>'',
                        'work_duration'=>'',
                        'office_address'=>'',
                        'salary'=>'',
                        'other_salary'=>'',
                        'loan_installment'=>'',
                        'dependent_amount'=>'',
                        'couple_salary'=>'',
                        'couple_other_salary'=>'',
                        'couple_loan_installment'=>'',
                        'emergency_name'=>'',
                        'emergency_mobile_phone'=>'',
                        'emergency_relation'=>'',
                        'identity'=>'',
                        'first_name'=>'',
                        'last_name'=>'',
                        'verify_status'=>'',
                        'gender'=>''
                        
                        // 'verify_status' => 'required|in:verify,verified',
                        // 'cif_number' => '',
                        // 'first_name' => 'required_if:verify_status,verify',
                        // 'last_name' => '',
                        // 'gender' => 'required_if:verify_status,verify',
                        // // 'birth_place_id' => 'required_if:verify_status,verify',
                        // 'birth_date' => 'required_if:verify_status,verify',
                        // 'phone' => 'required_if:verify_status,verify',
                        // 'mobile_phone' => 'required_if:verify_status,verify|regex:(08)',
                        // 'address' => 'required_if:verify_status,verify',
                        // // 'citizenship_id' => 'required_if:verify_status,verify',
                        // // 'status' => 'required_if:verify_status,verify|in:0,1,2',
                        // 'address_status' => 'required_if:verify_status,verify',
                        // 'mother_name' => 'required_if:verify_status,verify'
                    ];
                } else {
                    return [
                        'nik' => 'required|numeric|digits:16|unique:customer_details,nik,' . $this->customer . ',user_id',
                        'email' => 'required|email|unique:users,email,' . $this->customer,
                        'first_name' => 'required',
                        'birth_place_id' => 'required',
                        'birth_date' => 'required|date',
                        'address' => 'required',
                        'gender' => 'required|in:L,P',
                        'city_id' => 'required|numeric|exists:cities,id',
                        'phone' => 'required',
                        'citizenship_id' => 'required',
                        'status' => 'required|in:0,1,2',
                        'couple_nik' => 'required_if:status,1|numeric|digits:16',
                        'couple_name' => 'required_if:status,1',
                        'couple_birth_place_id' => 'required_if:status,1',
                        'couple_birth_date' => 'required_if:status,1|date',
                        'couple_identity' => 'required_if:status,1|image',
                        'address_status' => 'required',
                        'mother_name' => 'required',
                        'mobile_phone' => 'required|regex:(08)',
                        'emergency_contact' => 'required',
                        'emergency_relation' => 'required',
                        'job_type_id' => 'required',
                        'job_id' => 'required',
                        'company_name' => 'required',
                        'job_field_id' => 'required',
                        'position' => 'required',
                        'work_duration' => 'required',
                        'office_address' => 'required',
                        'salary' => 'required|numeric',
                        'other_salary' => 'required|numeric',
                        'loan_installment' => 'required',
                        'dependent_amount' => 'required',
                        'legal_document' => 'required|file',
                        'salary_slip' => 'required|file',
                        'identity' => 'image|mimes:jpg,jpeg,png',
                        'image' => 'image|mimes:jpg,jpeg,png',
                        'npwp' => 'required|image|mimes:jpg,jpeg,png',
                        'bank_statement' => 'required|file',
                        'family_card' => 'required|file',
                        'marrital_certificate' => 'required_if:status,1|file',
                        'diforce_certificate' => 'required_if:status,2|file'
                    ];
                }
                break;
            
            default:
                return [
                    //
                ];
                break;
        }
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        if( $this->has( 'status' ) & $this->status != '1' ) {
            return $this->except( [ 'couple_nik', 'couple_name', 'couple_birth_place_id', 'couple_birth_date', 'couple_identity' ] );
        }
        return $this->all();
    }
}
