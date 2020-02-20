<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MstInvestor extends Model
{
	public $timestamps = false;
    protected $table = 'mst_investor';
    protected $fillable = [
        'code',
    	'member_id',
    	'username',
    	'password',
    	'full_name',
    	'gender',
    	'birth_date',
    	'birth_place',
    	'address',
    	'villages_id',
    	'zip_code',
    	'email',
    	'phone',
    	'photo',
    	'ip_address',
    	'token_phone',
    	'remember_token',
    	'register_on',
    	'ktp_number',
    	'ktp_photo',
    	'npwp_number',
    	'npwp_photo',
        'currency',
    	'activation_code',
    	'forgot_pass_code',
    	'forgot_pass_time',
    	'active',
    	'created_at_investor',
    	'created_by_emp',
    	'created_at_emp',
    	'created_at_investor',
    	'updated_at_emp',
    	'updated_by_emp',
    	'deleted_at_emp',
    	'deleted_by_emp',
    	'is_deleted',
        'is_dummy',
        'is_completed'
    ];

    public function member()
    {
        return $this->belongsTo('App\Models\MstMember');
    }

    public function aptBooking()
    {
        return $this->hasMany('App\Models\AptBooking');
    }

    public function randomInvest($class_asset_id)
    {
        $result = true;
        $message = [];

        $min_invest = 0;
        $random_invest_amount = [];
        if($class_asset_id == 1) {
            $min_invest = 1000000;
            $random_invest_amount = [
                100000,
                500000,
                1000000
            ];
        }
        elseif ($class_asset_id == 2) {
            $min_invest = 50000000;
            $random_invest_amount = [
                50000000,
                75000000,
                80000000
            ];
        }
        elseif ($class_asset_id == 3) {
            $min_invest = 100000000;
            $random_invest_amount = [
                100000000,
                125000000,
                150000000
            ];
        }

        try {
            $assets = DB::select("
                SELECT
                    * 
                FROM
                    mst_asset 
                WHERE
                    is_deleted = 0 
                    AND active = 1 
                    AND ( SELECT get_sisa_investasi_asset ( id ) ) >= ? 
                    AND ( SELECT get_sisa_tenor_asset ( id ) ) > 20 
                    AND class_id =?
            ", [$min_invest, $class_asset_id]);

            if(!empty($assets)) {
                foreach($assets as $asset) {
                    $key = array_rand($random_invest_amount);
                    $amount = $random_invest_amount[$key];
                    // echo $amount.'<br>';
                    $total_investor = rand(1,3);
                    // echo $total_investor.'<br>';
                    $investors = DB::select("
                        SELECT
                            b.* 
                        FROM
                            tmp_investor a
                            LEFT JOIN mst_investor b ON a.email = b.email 
                        WHERE
                            b.is_deleted = 0 
                            AND b.active = 1 
                            AND b.is_dummy = 1 
                            AND ( SELECT f_investor_balance ( b.id ) ) >= ?
                        ORDER BY RAND() LIMIT ?
                    ", [$amount, $total_investor]);
                    // print_r($investors).'<br>';
                    // echo "<br><br>";
                    foreach($investors as $investor) {
                        $transaction_number = $this->transactionNumber();
                        $transaction = DB::table('trc_transaction_asset')->insertGetId([
                            'investor_id' => $investor->id,
                            'currency_code' => 'IDR',
                            'transaction_number' => $transaction_number,
                            'date' => date('Y-m-d H:i:s'),
                            'status' => 'PAID',
                            'information' => '(Dummy) Transaction Asset Payment',
                            'total_amount' => $amount,
                            'active' => 1,
                            'verified' => 'N'
                        ]);

                        if($transaction) {
                            $transaction_number_detail = $transaction_number.'/1';
                            $detail = DB::table('trc_transaction_detail_asset')->insertGetId([
                                'trc_asset_id' => $transaction,
                                'asset_id' => $asset->id,
                                'transaction_number_detail' => $transaction_number_detail,
                                'invest_tenor' => 20,
                                'number_interest' => $asset->interest,
                                'amount' => $amount,
                                'active' => 1,
                                'is_deleted' => 0
                            ]);

                            $balance_out = DB::table('trc_transaction_balance_out')->insertGetId([
                                'investor_id' => $investor->id,
                                'currency_code' => 'IDR',
                                'transaction_asset_id' => $transaction,
                                'transaction_number' => $transaction_number,
                                'date' => date('Y-m-d H:i:s'),
                                'amount' => $amount,
                                'status' => 'VERIFIED',
                                'information' => '(Dummy) Transaction Asset Payment',
                                'active' => 1
                            ]);

                            if($balance_out) {
                                DB::select("call tran_balance_update(?, ?, ?, ?, ?, ?, ?, ?, ?, @vret)", [
                                    $investor->id,
                                    $transaction,
                                    $transaction_number,
                                    date('Y-m-d H:i:s'),
                                    $amount,
                                    '(Dummy) Transaction Asset Payment',
                                    'y',
                                    'out',
                                    'IDR'
                                ]);
                                $vret = DB::select("select @vret as vret")[0]->vret;

                                $message[] = [
                                    'status' => $vret,
                                    'investor_id' => $investor->id,
                                    'email' => $investor->email,
                                    'asset_id' => $asset->id,
                                    'transaction_id' => $transaction,
                                    'transaction_number' => $transaction_number,
                                    'amount' => $amount,
                                    'tenor' => 20
                                ];
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $result = false;
        }

        return [
            'result' => $result,
            'response' => $message
        ];
    }

    private function transactionNumber()
    {
        date_default_timezone_set('Asia/Jakarta');
        $trc_now = "TRS/03/".date('ymd')."/";
        $sql_query = DB::select("SELECT MAX(RIGHT(transaction_number,4)) AS max_code FROM trc_transaction_asset WHERE LEFT(transaction_number,14) = ?", [$trc_now])[0]->max_code;

        $trs_code = "";
        if($sql_query == null) {
            $trs_code = '0001';
        } else {
            $trs_code = sprintf("%04s", ((int)$sql_query+1));
        }
        $transaction_code = $trc_now.$trs_code;
        return $transaction_code;
    }

    /*================================================================*/
    public function getBalance($investor_id)
    {
        $model = DB::select("select f_investor_balance(?) as balance", [$investor_id])[0]->balance;
        return $model == null ? 0 : $model;
    }

    public function getTotalActiveInvestment($investor_id)
    {
        $model = DB::select("select f_investor_investment(?) as balance", [$investor_id])[0]->balance;
        return $model == null ? 0 : $model;
    }

    public function getTotalActiveAsset($investor_id)
    {
        $model = DB::select("select f_investor_total_asset(?) as balance", [$investor_id])[0]->balance;
        return $model == null ? 0 : $model;
    }

    public function getSummaryInvestment($investor_id)
    {
        $model = DB::select("select f_investor_summary_investment(?) as balance", [$investor_id])[0]->balance;
        $list = explode('|', $model);
        return [
            'active' => $list[0],
            'inactive' => $list[1],
            'canceled' => $list[2]
        ];
    }

    public static function getCode()
    {
        return DB::select('select get_investor_code() as code')[0]->code;
    }

}
