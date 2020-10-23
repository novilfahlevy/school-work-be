<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_has_roles');
    }

    public function loans()
    {
        return $this->hasMany('App\Models\Loan');
    }

    /**
     * Get the user role name by the collection of user data
     *
     * @param  mixed $user
     * @return string
     */
    public static function getUserRoleName($user)
    {
        foreach ($user->roles as $key => $role) {
            return $role->name;
        }
    }

    /**
     * Wrapping the user details data
     *
     * @param  mixed $id
     * @return array
     */
    public static function detailsOfUser($id)
    {
        $user = User::find($id);

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'gender' => get_gender_name($user),
            'email' => $user->email,
            'phoneNumber' => $user->phone_number,
            'joinDate' => indonesian_date_format($user->join_date),
            'birthDate' => indonesian_date_format($user->birth_date),
            'job' => $user->job,
            'loans' => Loan::getLoansDataByUserId($user->id,)
        ];

        return $data;
    }

    /**
     * Wrapping the employees data
     *
     * @return array
     */
    public static function listOfEmployees()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('role_id', 2);
        })->orderBy('name', 'ASC')->get();

        foreach ($employees as $key => $employee) {
            $data[$key] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'gender' => get_gender_name($employee),
                'email' => $employee->email,
                'phoneNumber' => $employee->phone_number,
                'joinDate' => indonesian_date_format($employee)
            ];
        }

        return $data;
    }

    /**
     * Wrapping the employee details data
     *
     * @param  mixed $id
     * @return array
     */
    public static function detailsOfEmployee($id)
    {
        $employee_details = User::findOrFail($id);

        $data['id'] = $employee_details->id;
        $data['name'] = $employee_details->name;
        $data['gender'] = get_gender_name($employee_details);
        $data['email'] = $employee_details->email;
        $data['phoneNumber'] = $employee_details->phone_number;
        $data['joinDate'] = indonesian_date_format($employee_details->join_date);
        $data['birthDate'] = indonesian_date_format($employee_details->birth_date);
        $data['job'] = $employee_details->job;
        $data['deposits'] = Deposit::getDepositDataByUserId($employee_details->id);

        return $data;
    }
}
