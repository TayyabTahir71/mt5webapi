<?php

namespace Tayyab\MT5WebApi\Controllers;


use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Tayyab\MT5WebApi\MTConGroup;
use Tayyab\MT5WebApi\MTDeal;
use Tayyab\MT5WebApi\MTOrder;
use Tayyab\MT5WebApi\MTPosition;
use Tayyab\MT5WebApi\MTWebAPI;
use Tayyab\MT5WebApi\MTUser;
use Tayyab\MT5WebApi\MTAccount;
use Illuminate\Support\Facades\Log;

/**
 * Class MT5Controller
 * @package Tayyab\MT5WebApi\Controllers
 */
class MT5Controller extends Controller
{
    protected $api;

    /**
     * MT5Controller constructor.
     * @param MTWebAPI $api
     */
    public function __construct(MTWebAPI $api)
    {
        $api->Connect(
            config('metaquotes.mt5.ip'),
            config('metaquotes.mt5.port'),
            30,
            config('metaquotes.mt5.login'),
            config('metaquotes.mt5.password'));

        $api->SetLoggerIsWrite(true);
        $api->SetLoggerFilePath(storage_path('logs'));
        $api->SetLoggerWriteDebug(true);

        $this->api = $api;
    }

    public function index(){

        $data = array(
            'here' => 'This is the home page - other views coming soon',
        );
        return view('MT5WebApi::index',$data);
    }

    /**
     * Returns the full details of the MT5 Account
     *
     * @param $meta_id
     * @param bool $justData
     * @return false|string |null
     */
    public function account($meta_id, $justData = false) {

        setlocale(LC_MONETARY,"en_US");
        date_default_timezone_set(config('metaquotes.timezone'));
        $account = null;

        try {

            $this->api->UserAccountGet($meta_id,$account);

        } catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            \Log::emergency('Account ID:'.$meta_id.' Error '.$e->getMessage());

        }

        if ( $justData ) {
            return json_encode($account);
        }

        $data = array(
            'account' => $account,
        );
        dd($data);
        return view('MT5WebApi::index',$data);

    }

    /**
     * Returns all the MT5 user info to be used on the contact view page.
     *
     * @param int $meta_id MT5 Id of the contact
     * @param bool $justData
     * @return MTUser
     */
    public function user($meta_id, $justData = false) {

        setlocale(LC_MONETARY,config('metaquotes.locale'));
        date_default_timezone_set(config('metaquotes.timezone'));


        $user = new MTUser();

        try {

            $this->api->UserGet($meta_id,$user);

        } catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            Log::emergency('User ID:'.$meta_id.' Error '.$e->getMessage());

        }

        if ( $justData ) {
            return json_encode($user);
        }

        $data = array(
            'user' => $user,
        );
        return view('MT5WebApi::index',$data);


    }

    /**
     * Gets the user/account details only for use in this class
     *
     * @param $meta_id
     * @return MTUser
     */
    private function getUser($meta_id) {

        setlocale(LC_MONETARY,config('metaquotes.locale'));
        date_default_timezone_set(config('metaquotes.timezone'));

        $user = new MTUser();

        try {

            $this->api->UserGet($meta_id,$user);

        } catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            Log::emergency('User ID:'.$meta_id.' Error '.$e->getMessage());

        }


        return $user;


    }

    /**
     * Get User Balance
     *
     * @param $meta_id
     * @return false|string
     */
    public function balance($meta_id) {

        $user = new MTUser();
        $mt_account = new MTAccount();

        try {

            $this->api->UserGet($meta_id,$user);

        } catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            Log::emergency('User ID:'.$meta_id.' Error '.$e->getMessage());

        }

        $ret = array(
            'balance' => $user->Balance,
        );

        return json_encode($ret);

    }

    /**
     * Get the list of open positions for this user
     *
     * @param $meta_id
     * @param bool $justData
     * @return array
     */
    public function positions($meta_id,$justData = false) {

        $mt_positions = null;
        try {

            $mt_positions = array(new MTPosition() );
            $this->api->PositionGetPage($meta_id,0,100,$mt_positions);

        } catch (\Exception $e) {
            Log::emergency('Positions Error  '.$e->getMessage());
        }

        if ( $justData ) {
            return json_encode($mt_positions);
        }

        $data = array(
            'positions' => $mt_positions,
        );

        return view('MT5WebApi::index',$data);



    }

    /**
     * Get the list of orders for this user
     *
     * @param $meta_id
     * @param bool $justData
     * @return array
     */
    public function orders($meta_id,$justData = false) {

        $mt_orders = null;

        try {

            $mt_orders = array(new MTOrder()); // OrderGetPage($login, $offset, $total, &$orders)
            //$mt_positions->Login = $login;
            $this->api->OrderGetPage($meta_id, 0, 100, $mt_orders);


        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            Log::emergency('Orders Error '.$e->getMessage());
        }

        if ( $justData ) {
            return json_encode($mt_orders);
        }

        $data = array(
            'orders' => $mt_orders,
        );
        return view('MT5WebApi::index',$data);


    }

    /**
     * Get the list of deals for this user
     *
     * @param $meta_id
     * @param bool $justData
     * @param int $offset
     * @return array
     */
    public function deals($meta_id,$justData = false, $offset = 0) {

        $mt_deals = null;

        try {

            $mt_deals = array(new MTDeal() ); // DealGetPage($login, $from, $to, $offset, $total, &$deals)

            $from = Carbon::now()->subDays(365)->timestamp;
            $to = Carbon::now()->timestamp;
            $total = 100;

            $this->api->DealGetPage($meta_id,$from,$to,$offset,$total,$mt_deals);

        } catch (\Exception $e) {
            Log::emergency('Deals Error '.$e->getMessage());
        }

        if ( $justData ) {
            return json_encode($mt_deals);
        }

        $data = array(
            'deals' => $mt_deals,
        );

        return view('MT5WebApi::index',$data);

    }

    /**
     * Get the list of symbols this user/group has access to
     *
     * @param $meta_id
     * @param bool $justData
     * @return false|string
     */
    public function symbols($meta_id,$justData = false){

        $mt_group = null;
        $groups_symbols = null;
        try {

            $mt_group = new MTConGroup();

            $account = self::getUser($meta_id);
            $group = 'real\\real';//$account->Group;
            $this->api->GroupGet($group,$mt_group);
            //dd( $mt_group );
            $groups_symbols = $mt_group->Symbols;

        } catch (\Exception $e) {
            Log::emergency('Positions Error  '.$e->getMessage());
        }

        if ( $justData ) {
            return json_encode($groups_symbols);
        }

        $data = array(
            'symbols' => $groups_symbols,
        );

        return view('MT5WebApi::index',$data);

    }

    /**
     * Sends News Into the Platform Accounts
     *
     * @param string $subject
     * @param string $category
     * @param $html_message
     * @param string $language
     * @param string $priority
     * @return bool
     */
    public function sendNews($subject,$category,$html_message,$language = '0',$priority = '1') {

        try{
            $this->api->NewsSend($subject,$category,$language,$priority,$html_message);
        }catch (\Exception $e){
            Log::emergency('Sending News Error '.$e->getMessage());
            return false;
        }

        return true;

    }

    /**
     * Sends Messages to the Traders Platform Inbox
     *
     * @param int $meta_id
     * @param string $subject
     * @param $html_message
     * @return bool
     */
    public function sendMail($meta_id,$subject,$html_message) {

        try{
            $this->api->MailSend($meta_id,$subject,$html_message);
        }catch (\Exception $e){
            Log::emergency('Sending Mail Error '.$e->getMessage());
            return false;
        }

        return true;

    }

    /**
     *
     * @param array $account_details
     * @return |null
     */
    public function create($account_details){

        $user_login = null;

        try{
            $new_user = $this->api->UserCreate();

            $new_user->Email        = $account_details['email'];
            $new_user->MainPassword = $account_details['main_password'];

            $new_user->LeadSource   = $account_details['LeadSource'] ? $account_details['LeadSource'] : null;
            $new_user->Group        = $account_details['Group'] ? $account_details['Group'] : 'demo\\demo';
            $new_user->Leverage     = $account_details['Leverage'] ?  $account_details['Leverage'] : 1 ;
            $new_user->ZipCode      = $account_details['ZipCode'] ? $account_details['ZipCode'] : null;
            $new_user->Country      = $account_details['Country'] ? $account_details['Country'] : null;
            $new_user->State        = $account_details['State'] ? $account_details['State']  : null;
            $new_user->City         = $account_details['City'] ?$account_details['City'] : null;
            $new_user->Address      = $account_details['Address'] ?$account_details['Address'] : null;
            $new_user->Phone        = $account_details['Phone'] ? $account_details['Phone'] : null ;
            $new_user->Name         = ucwords($account_details['FirstName'].' '. $account_details['LastName']);
            $new_user->PhonePassword    = $account_details['PhonePassword'];
            $new_user->InvestPassword   = $account_details['InvestPassword'];

            $this->api->UserAdd($new_user, $user_server);

            $user_login = $user_server->Login;


        }
        catch (\Exception $e){
            Log::emergency('Create Account Error  '.$e->getMessage());
        }


        return $user_login;

    }

}
