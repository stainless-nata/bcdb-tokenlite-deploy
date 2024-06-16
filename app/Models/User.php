<?php /** @noinspection ALL */

/**
 * User Model
 *
 * The User Model
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use App\BigChainDB\BigChainModel;
use Carbon\Carbon;
use App\Models\KYC;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * @property mixed walletAddress
 */
class User extends BigChainModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    /*
     * Table Name Specified
     */
    protected static $table = 'users';

    public function getKeyName()
    {
        return 'id';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'lastLogin', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     *
     * Relation with kyc
     *
     * @version 1.0.0
     * @since 1.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kyc_info()
    {
        return $this->belongsTo('App\Models\KYC', 'id', 'userId')->orderBy('created_at', 'DESC');
    }

    /**
     *
     * Relation with meta
     *
     * @version 1.0.0
     * @since 1.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meta()
    {
        return $this->belongsTo('App\Models\UserMeta', 'id', 'userId');
    }
    
    /**
     *
     * Relation with meta
     *
     * @version 1.0.0
     * @since 1.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function access()
    {
        return $this->belongsTo('App\Models\GlobalMeta', 'id', 'pid')->where(['name' => 'manage_access'])->withDefault(['name' => 'manage_access', 'value' => 'default', 'extra' => json_encode(['all'])]);
    }

     /**
     *
     * Relation with Activity logs
     *
     * @version 1.0.0
     * @since 1.0
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logs()
    {
        return $this->belongsTo('App\Models\Activity', 'id', 'user_id');
    }

     /**
     *
     * Check user role
     *
     * @version 1.0.0
     * @since 1.1.5
     * @return boolean
     */
    public function is($name)
    {
        return $this->role == $name;
    }

    /**
     * Data of Advanced search and export
     *
     * @version 1.0.0
     * @since 1.1
     * @return self 
     */
    public static function AdvancedFilter(Request $request)
    {
        if($request->s){
            $users = User::whereNotIn('status', ['deleted'])->where('role', 'user')
                        ->where(function($q) use ($request){
                            $id_num = (int)(str_replace(config('icoapp.user_prefix'), '', $request->s));
                            $q->orWhere('id', $id_num)->orWhere('email', 'like', '%'.$request->s.'%')->orWhere('name', 'like', '%'.$request->s.'%');
                        });
            return $users;
        }

        if ($request->filter) {
            $users = User::whereNotIn('status', ['deleted'])
                        ->where(function($q) use ($request){
                            $roles = ($request->adm && $request->adm=='yes') ? ['user', 'admin'] : ['user'];
                            $q->whereIn('role', $roles)->where( self::keys_in_filter($request->only(['wallet', 'state', 'reg', 'token', 'refer'])) );
                        })
                        ->when($request->valid, function($q) use ($request){
                            $kyc_ids = KYC::where('status', 'approved')->pluck('userId');
                            if($request->valid == 'email'){
                                $q->whereNotNull('email_verified_at');
                            }
                            if($request->valid == 'kyc'){
                                $q->whereIn('id', $kyc_ids);
                            }
                            if($request->valid == 'both'){
                                $q->whereIn('id', $kyc_ids)->whereNotNull('email_verified_at');
                            }
                        })
                        ->when($request->search, function($q) use ($request){
                            $where  = (isset($request->by) && $request->by!='') ? strtolower($request->by) : 'name';
                            $search = ($where=='id') ? (int)(str_replace(config('icoapp.user_prefix'), '', $request->search)) : $request->search;
                            if($where=='id') {
                                $q->where($where, $search);
                            } else {
                                $q->where($where, 'like', '%'.$search.'%');
                            }
                        });
            return $users;
        }
        return $this;
    }

    /**
    * Search/Filter parametter exchnage with database value
    *
    * @version 1.0.0
    * @since 1.1.0
    * @return void
    */
    protected static function keys_in_filter($request) {
        $result = [];
        $find = ['wallet', 'state', 'reg', 'token', 'refer'];
        $replace = ['walletType', 'status', 'registerMethod', 'tokenBalance', 'referral'];
        foreach($request as $key => $value) {
            $set_key = str_replace($find, $replace, $key);
            $val = trim($value);

            if(!empty($val)) {
                if($set_key=='walletType') {
                    $result[] = array($set_key, '!=', null);
                }elseif($set_key=='tokenBalance') {
                    $result[] = array($set_key, ($val=='has' ? '>' : '='), ($val=='has' ? 0 : null));
                } elseif($set_key=='referral') {
                    $result[] = array($set_key, ($val=='yes' ? '!=' : '='), null);
                } else {
                    $result[] = array($set_key, '=', $val);
                }
            }
        }
        return $result;
    }

    /**
     *
     * Relation with transaction
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function first_tnx()
    {
        $user = $this;
        $tnx = Transaction::where('user', $user->id)->first();
        return $tnx;
    }

    /**
     *
     * Relation with referral
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function referee()
    {
        return $this->belongsTo(self::class, 'referral', 'id');
    }
/**
     *
     * Get Referrals
     *
     * @version 1.0.0
     * @since 1.0.3
     * @return void
     */
    public function referrals()
    {
        return User::where('referral', $this->id)->get();
    }

    public function generateSecret()
    {
        $secret = hash('joaat', gdmn());
        $item = Setting::where('field', 'LIKE', "%_lkey")->first();
        if( $item && str_contains($item->value, $secret)){
            add_setting('site_api_secret', str_random(4).$secret.str_random(4));
            return true;
        }
        add_setting('site_api_secret', str_random(16) );
        return true;
    }

    /**
     *
     * Check if request to change wallet address and it's status
     *
     * @version 1.0.0
     * @since 1.0
     * @return string
     */
    public function wallet($output='status')
    {
        $wrc = GlobalMeta::where(['pid' => $this->id, 'name' => 'user_wallet_address_change_request'])->first();
        $return = false;
        if ($wrc && ($this->walletAddress != $wrc->data()->address)) {
            $return = 'pending';
        }
        $return = ($output=='current') ? $this->walletAddress : $return;
        $return = ($output=='new') ? $wrc->data()->address : $return;
        return $return;
    }

    /**
     *
     * Data of dashboard
     *
     * @version 1.1
     * @since 1.0
     * @param int $get
     * @return object
     */
    public static function dashboard($get = 15)
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $data['all'] = self::count();
        $data['last_week'] = self::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $data['kyc_last_week'] = KYC::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $data['unverified'] = ceil(((self::where('email_verified_at', null)->count()) * 100) / self::count());
        $data['verified'] = (100 - $data['unverified']);
        $data['kyc_submit'] = KYC::count();
        $data['kyc_approved'] = KYC::where('status', 'approved')->count();
        $data['kyc_pending'] = KYC::count() > 0 ? ceil(((KYC::where('status', 'pending')->count()) * 100) / KYC::count()) : 0;
        $data['kyc_missing'] = KYC::count() > 0 ? ceil(((KYC::where('status', 'missing')->count()) * 100) / KYC::count()) : 0;

        $data['chart'] = self::chart($get);

        return (object) $data;
    }
    /**
     *
     * Chart data
     *
     * @version 1.1
     * @since 1.0
     * @return object
     */
    public static function chart($get = 15)
    {
        $cd = Carbon::now(); //->toDateTimeString();
        $lw = $cd->copy()->subDays($get);

        $cd = $cd->copy()->addDays(1);
        $df = $cd->diffInDays($lw);

        $data['days'] = null;
        $data['data'] = null;
        $data['data_alt'] = null;
        $data['days_alt'] = null;
        $usr = 0;
        for ($i = 1; $i <= $df; $i++) {
            $usr = self::whereDate('created_at', $lw->format('Y-m-d'))->count();
            $data['data'] .= $usr . ",";
            $data['days'] .= '"' . $lw->format('D') . '",';
            $data['data_alt'][$i] = $usr;
            $data['days_alt'][$i] = ($get > 27 ? $lw->format('D, d M') : $lw->format('D'));
            $lw->addDay();
        }
        return (object) $data;
    }
}
