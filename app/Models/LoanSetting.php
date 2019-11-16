<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class LoanSetting extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_setting_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getLoanSettings()
    {
        $query = DB::table('loan_settings');
        $query->select(
            'loan_settings.*',
            DB::raw('CONCAT(users.firstname, " ", users.lastname) as created_by')
        );
        $query->join('users', 'users.user_id', '=', 'loan_settings.user_id');
        $query->orderBy('loan_setting_id', 'DESC');
        return $query->get();
    }

    public function getLoanSetting($loanSettingId)
    {
        $query = DB::table(
            'loan_settings',
            DB::raw('CONCAT(users.firstname, " ", users.lastname) as created_by')
        );
        $query->select('loan_settings.*');
        $query->join('users', 'users.user_id', '=', 'loan_settings.user_id');
        $query->where('loan_settings.loan_setting_id', '=', $loanSettingId);
        return $query->get();
    }
}
