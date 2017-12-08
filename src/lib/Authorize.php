<?php 

namespace CallDoc\Lib;

use CallDoc\Models\Users;
use CallDoc\Models\BlackList;
use CallDoc\Models\Roles;
use CallDoc\Models\Tokens;

use Carbon\Carbon;


/**
 *  Check if a user is allowed to perform a particular action
 */
class Authorize 
{
    public const ADMIN = 'ADMIN';
    public const DOCTOR = 'DOCTOR';
    public const PATIENT = 'PATIENT';

  
    /**
     * check if user can add answer
     * 
     * @param string $token
     * 
     * @return boolean
     */
    public static function canAddAnswer($token)
    {
        $role = self::getRole($token);
        if ($role == self::ADMIN || $role == self::DOCTOR) {
            return true;
        }
        return false;
    }

    /**
     * check if user can view patient
     * 
     * @param string $token
     * 
     * @return boolean
     */
    public static function canViewPatient($token)
    {
        $role = self::getRole($token);
        if ($role == self::ADMIN || $role == self::DOCTOR) {
            return true;
        }
        return false;
    }

    /**
     * check if user is authorized to act on patients
     * 
     * @param string $token
     * 
     * @return boolean
     */
    public static function canActOnUser($token)
    {
        $role = self::getRole($token);
        if ($role == self::ADMIN || $role == self::DOCTOR) {
            return true;
        }
        return false;
    }


    /**
     * check to enusre user is not black listed
     * 
     * @param int $user_id
     * @param int $docs_id
     * 
     * @return boolean
     */
    public static function canViewDoctor($user_id, $docs_id)
    {
        $blist = BlackList::where(['user_id' => $user_id, 'doctors_id' => $docs_id, 'black_listed' => 1])->first();
        if (!is_null($blist)) {
            return true;
        }
        return false;
    }

    /**
     * get a user's role with token
     * 
     * @param string $token
     * @return string $role[]
     */
    public static function getRole($token)
    {
        $user_id = Tokens::where(['value' => $token])
                                                    ->whereDate('expiry', '>=', Carbon::today()->toDateString())
                                                    ->pluck('user_id')->toArray()[0];
        
        $user = Users::where(['id' => $user_id])->get(['role_id'])->first();
        $role = Roles::where(['id' => $user['role_id']])->get()->toArray()[0];
        return strtoupper($role['role']);
    }


    /**
     * get role id of user
     * 
     * @param $role const
     * 
     * @return int $role_id
     */
    public static function getRoleId($role)
    {
        return Roles::where(['role' => $role])->get()->toArray()[0];
    }

}