<?php
namespace App;

use App\Models\AptBooking;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Models\MstEmployee;
use App\Models\MstInvestor;
use App\Models\MstRedeemLang;
use App\Models\MstRedeemVoucher;
use Illuminate\Support\Facades\DB;

class Utility
{
    public function getInfoNotaris($id)
    {
        $query = DB::table('m_notaris as a')->where('a.id', $id)->get();

        foreach ($query as $key ) {
            $result = $key->name;
        }
        return $result;
    }

    public function getUserNew()
    {
        $userId = Auth::id();
        $query = DB::table('mst_employee')->where('id', $userId)->get();

        foreach ($query as $key ) {
            $result = $key->is_notaris;
        }

        return $result;
    }



    public function SquanceCode($id)
    {
        $query = DB::select("SELECT max(code_unit) as maxCode FROM mst_unit_apt WHERE is_deleted = 0 AND code_unit like '%$id%'");
        foreach ($query as $key ) {
            $urut = $key->maxCode;
        }

        $noUrut = (int) substr($urut, 5, 8);
        $noUrut++;

        $code = sprintf("%03s", $noUrut);
        return $code;
    }

    public function SquanceCodeFloor()
    {
        $query = DB::select("SELECT max(code_floor) as maxCode FROM mst_floor_apt");
        foreach ($query as $key ) {
            $urut = $key->maxCode;
        }

        $noUrut = (int) substr($urut, 3, 5);
        $noUrut++;

        $code = sprintf("%03s", $noUrut);
        return $code;
    }

	public function getUser($id)
    {
    	$fullname = '';
    	$model = DB::table('mst_employee')
                        ->where('id','=',$id)
                        ->first();
    	if($model)
    		$fullname = $model->full_name;

    	return $fullname;
    }

    public function localDate($date)
    {
        return date('d M Y H:i', strtotime($date)).' WIB';
    }

    public function getDetailAddress($id) //village_id
    {
        $data = DB::table('mst_villages as v')
                ->join('mst_districts as d','v.districts_id','=','d.id')
                ->join('mst_regencies as r','d.regencies_id','=','r.id')
                ->join('mst_regencies_lang as rl','rl.regencies_id','=','r.id')
                ->join('mst_provinces as p','r.provinces_id','=','p.id')
                ->join('mst_countries as c','p.countries_id','=','c.id')
                ->select('v.name as village', 
                        'd.name as district', 
                        'rl.name as regency', 
                        'p.name as province', 
                        'c.name as country',
                        'v.id as vid',
                        'd.id as did',
                        'r.id as rid',
                        'p.id as pid',
                        'c.id as cid'
                )
                ->where('v.id',$id)
                ->where('rl.code',"IND")
                ->first();
        $village = $data->village;
        $district = $data->district;
        $regency = $data->regency;
        $province = $data->province;
        $country = $data->country;
        return [
            'village'  => $village,
            'district'  => $district,
            'regency'  => $regency,
            'province'  => $province,
            'country'  => $country,
            'village_id'  => $data->vid,
            'district_id'  => $data->did,
            'regency_id'  => $data->rid,
            'province_id'  => $data->pid,
            'country_id'  => $data->cid
        ];
    }

    public function paymentMethod()
    {
        return [
            '1' => 'Internet Banking',
            '2' => 'Manual Transfer',
            '3' => 'Virtual Account',
            '4' => 'Indomaret'
        ];
    }

    public function getPaymentMethod($id)
    {
        $value = '';
        foreach($this->paymentMethod() as $key => $value)
        {
            if($id == $key)
            {
                $value = $value;
                break;
            }
        }
        return $value;
    }

    public function listMenu()
    {
        $id = Auth::user()->role_id;

        $menus = '';
        // get menu level 1
        $parents = DB::table('sec_accesslevel as a')
                ->join('sec_module as b','a.module_id','=','b.id')
                ->where('a.role_id',$id)
                ->where('b.parent_id',null)
                ->select('a.module_id as id','b.module','b.link','b.icon')
                ->groupBy('a.module_id','b.module')
                ->orderBy('b.sort')
                ->get();
        // dd($id);
        // dd($parents);
        foreach ($parents as $first) 
        {
            $icon = $first->icon == '' ? 'fa fa-folder' : $first->icon;
            // cek if has child
            $childs = $this->getChild($first->id);
                    // dd($childs);
            
            if(!$childs)
            {
                $menus .= '<li>
                    <a href="'.$first->link.'">
                        <i class="'.$icon.'"></i>
                        <span>'.$first->module.'</span>
                    </a>
                </li>';
            }
            else
            {
                $menus .= '<li class="treeview">
                    <a href="#">
                        <i class="'.$icon.'"></i>
                        <span>'.$first->module.'</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>';
                $menus .= '<ul class="treeview-menu">';
                foreach ($childs as $second) {
                    $icon = $second->icon == '' ? 'fa fa-circle-o' : $second->icon;
                    $grandchilds = $this->getChild($second->id);
                    if(!$grandchilds)
                    {
                        $menus .= '<li>
                            <a href="'.$second->link.'">
                                <i class="'.$icon.'"></i>
                                <span>'.$second->module.'</span>
                            </a>
                        </li>';
                    }
                    else
                    {
                        $menus .= '<li class="treeview">
                            <a href="#">
                                <i class="'.$icon.'"></i>
                                <span>'.$second->module.'</span>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>';
                        $menus .= '<ul class="treeview-menu">';
                        foreach($grandchilds as $third)
                        {
                            $icon = $third->icon == '' ? 'fa fa-circle-o' : $third->icon;
                            $menus .= '<li>
                                <a href="'.$third->link.'">
                                    <i class="'.$icon.'"></i>
                                    <span>'.$third->module.'</span>
                                </a>
                            </li>';
                        }
                        $menus .= '</ul></li>';
                    }
                }
                $menus .= '</ul></li>';
            }
        }

        echo $menus;
    }

    private function getChild($id) // parent_id
    {
        return DB::table('sec_module as a')
                ->join('sec_accesslevel as b','a.id','=','b.module_id')
                ->where('a.parent_id', $id)
                ->where('a.active','1')
                ->where('a.is_deleted', '0')
                ->where('b.role_id', Auth::user()->role_id)
                ->select('a.id','a.module','a.parent_id','a.link','a.icon')
                ->groupBy('b.module_id','a.module')
                ->orderBy('a.sort')
                ->get()
                ->toArray();
    }

    public function bestSellingAsset($year, $month)
    {
        $rawdata = [];

        $query = "
        select 
            ai.asset_id as id,
            al.asset_name,
            count(ta.investor_id) as total
        from
            trc_transaction_asset as ta
        left join trc_asset_investor as ai on ta.id = ai.trc_asset_id
        join mst_asset as a on ai.asset_id = a.id
        join mst_asset_lang as al on a.id = al.asset_id
        where 
            al.code = 'IND'
        and lower(ai.status) = 'active' ";

        if($year != '' && $month != '')
        {
            $query .= "and date_format(ta.date, '%Y') = ? ";
            $query .= "and date_format(ta.date, '%m') = ? ";
            $query .= "group by 
                            ai.asset_id,
                            al.asset_name
                    order by 
                            count(ta.investor_id) desc
                    limit 10;";
            $rawdata = DB::select($query, [$year, $month]);
        }
        elseif($year == '' && $month == '')
        {
            $query .= "group by 
                            ai.asset_id,
                            al.asset_name
                    order by 
                            count(ta.investor_id) desc
                    limit 10;";
            $rawdata = DB::select($query);
        }
        elseif($year != '')
        {
            $query .= "and date_format(ta.date, '%Y') = ? ";
            $query .= "group by 
                                ai.asset_id,
                                al.asset_name
                        order by 
                                count(ta.investor_id) desc
                        limit 10; ";
            $rawdata = DB::select($query, [$year]);   
        }

        return $rawdata;
    }

    public function highestInvestment($year, $month)
    {
        $rawdata = [];

        $query = "
            select 
                ta.investor_id as id,
                i.full_name,
                sum(ta.total_amount) as amount
            from 
            trc_transaction_asset as ta 
            left join mst_investor as i on ta.investor_id = i.id 
            where 
                lower(ta.status)='paid'
            and lower(ta.verified)='y'
        ";

        if($year != '' && $month != '')
        {
            $query .= "and date_format(ta.date, '%Y') = ? ";
            $query .= "and date_format(ta.date, '%m') = ? ";
            $query .= "group by 
                        ta.investor_id,
                        i.full_name
                    order by 
                        sum(ta.total_amount) desc
                    limit 10;";
            $rawdata = DB::select($query, [$year, $month]);
        }
        elseif($year == '' && $month == '')
        {
            $query .= "group by 
                        ta.investor_id,
                        i.full_name
                    order by 
                        sum(ta.total_amount) desc
                    limit 10;";
            $rawdata = DB::select($query);   
        }
        elseif($year != '')
        {
            $query .= "and date_format(ta.date, '%Y') = ? ";
            $query .= "group by 
                            ta.investor_id,
                            i.full_name
                        order by 
                            sum(ta.total_amount) desc
                        limit 10; ";
            $rawdata = DB::select($query, [$year]);   
        }

        return $rawdata;
    }

    /*
    example : 
        $childTables = [trc_inv_bank]
        $fk_ids = [bank_id]
        $pk_id = 1
    */
    public function isAllowedToUpdate($childTables = array(), $fk_ids = array(), $pk_id)
    {
        // looping pengecekan data di child table
        $no = 0;
        $total = 0;
        foreach($childTables as $table)
        {
            $count = DB::table($table)
                    ->where($fk_ids[$no], $pk_id)
                    ->where('is_deleted', '0')
                    ->count();
            $total = $total + $count;

        }

        return $total > 0 ? false : true;
    }

    public function isAllowedToUpdate2($childTables = array(), $fk_ids = array(), $pk_id)
    {
        // looping pengecekan data di child table
        $no = 0;
        $total = 0;
        foreach($childTables as $table)
        {
            $count = DB::table($table)
                    ->where($fk_ids[$no], $pk_id)
                    ->count();
            $total = $total + $count;
        }

        return $total > 0 ? false : true;
    }

    public function USDIDRRate()
    {
        $client = new Client();
        $result = $client->get('http://apilayer.net/api/live?access_key=feb247b947ca8681726a7746766fec80&currencies=IDR&source=USD&format=1');
        $result = json_decode((string)$result->getBody());
        $rate = $result->quotes->USDIDR;
        return $rate;
    }

    public function investor($id)
    {
        $model = MstInvestor::find($id);
        return $model->full_name;
    }

    public function detailInvestor($id)
    {
        $model = MstInvestor::find($id);
        return $model;
    }

    public function employee($id)
    {
        $model = MstEmployee::find($id);
        return $model->full_name;
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = [];
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    /*==================================
    EMAIL
    ====================================*/
    public function sendAppNotif($device, $message) {
        // dd($device);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '{
            "registration_ids":'.$device.',
            "notification": '.$message.'
          }',
          CURLOPT_HTTPHEADER => array(
            "Accept: /",
            "Accept-Encoding: gzip, deflate",
            "Authorization: key=AAAAOYxEqEo:APA91bEHiVcTGsj0BA9E_BrN-VJRZI6gBLtNyxFfQ_lbKCDZdWkKSSbrqqAemGoOJWc1tjXzMDO6QkBKsTjlmDuw1zXn08di_SOg3XtPO6ANEUtwHfRbYAcld-y9l3T9XJpWqCdjWXKP",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Content-Type: application/json",
            "Host: fcm.googleapis.com",
            "Postman-Token: bde336ee-c15c-4809-a89f-3305b9d4a029,905efb84-4975-41ed-93e4-286d79cb3b73",
            "User-Agent: PostmanRuntime/7.17.1",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return 0;
        } else {
          return 1;
        }
        // if ($err) {
        //   echo "cURL Error #:" . $err;
        // } else {
        //   echo $response;
        // }
    }

    public function sendMail($email, $name, $subject, $message, $message_txt)
    {
        DB::select("call add_email_queue(?, ?, ?, ?, ?, '', '', 'ADMIN', ?, @vret);", [$email, $name, $subject, $message, $message_txt, Auth::id()]);
        $vret = DB::select("select @vret as vret")[0]->vret;
        $return = explode('|', $vret);
        $result = false;
        if($return[0] != '0000')
            $result = true;
        return $result;
    }

    public function withdrawalVerifiedEmailNotification($email, $name, $tran_date, $amount)
    {
        $subject = 'Withdraw Balance on '.$this->getDateEnglish($tran_date);
        $message = '<p>';
        $message .= 'Hai <b>'.$name.'</b>,<br>';
        $message .= 'Selamat, Anda telah menerima uang pada tgl '.$this->getDateIndonesia(date('Y-m-d')).' pukul '.date('H:i:s').' sebesar '.number_format($amount).' IDR atas penarikan saldo pada '.$this->getDateIndonesia($tran_date).' pukul '.date('H:i:s', strtotime($tran_date)).'. Klik tombol dibawah ini untuk melihat status penarikan saldo anda secara detail.<br><br>
            Congratulation, you have received money on '.$this->getDateEnglish(date('Y-m-d')).' at '.date('H:i:s').' for '.number_format($amount).' IDR for the balance withdrawal on '.$this->getDateEnglish($tran_date).' at '.date('H:i:s', strtotime($tran_date)).'. Click the button below to see the status of withdrawing your balance in detail.';
        $message .= '</p><br><br>';
        $message .= '<center><a href="https://kop-aja.com/Profile/transaksi?tab=withdraw" style="color: #fff; background-color: #bb1318; padding: 10px 18px; border-radius: 4px; text-decoration: none;">
                Check Balance
            </a></center><br><br>
            <p style="margin-left: 75%;">
                Regards,<br/><br/>
                <strong>Kop-Aja</strong>
            </p>';
        $message_txt = 'Selamat, Anda telah menerima uang pada tgl '.$this->getDateIndonesia(date('Y-m-d')).' pukul '.date('H:i:s').' sebesar '.number_format($amount).' IDR atas penarikan saldo pada '.$this->getDateIndonesia($tran_date).' pukul '.date('H:i:s', strtotime($tran_date)).'. Cek saldo anda di halaman Saldo';
        $this->sendMail($email, $name, $subject, $message, $message_txt);
    }

    public static function voucherCashbackEmailNotification($s='public', $voucher_id)
    {
        $model = MstRedeemVoucher::findOrFail($voucher_id);
        $description_ind = MstRedeemLang::where('code','IND')
            ->where('redeem_voucher_id',$voucher_id)
            ->first();
        $subject = 'Selamat, Anda mendapatkan Cashback Rp '.number_format($model->amount, 0, ',', '.');
        $uti = new Utility;
        if ($s == 'public') {
            $investors = MstInvestor::where('active', 1)
                ->where('is_completed', 1)
                ->where('is_deleted', 0)
                ->get();

            if (!empty($investors)) {
                foreach ($investors as $key => $value) {
                    $message_ind = 'Hai <b>'.$value->full_name.'</b>,<br>';
                    $message_ind .= 'Ada Cashback sebesar Rp '.number_format($model->amount, 0, ',', '.').' untuk Anda sebagai investor setia Kop-Aja.<br>';
                    $message_ind .= '<img src="https://backend.kop-aja.com/images/voucher/"'.$description_ind->image.' style="width:100%; margin:20px auto;" /><br>';
                    $message_ind .= 'Klik tombol di bawah ini untuk melihat cara untuk mendapatkan cashback ini.<br>';
                    $message_ind .= '<center><a href="https://kop-aja.com/voucher/detail_redeem/'.$model->id.'" style="color: #fff; background-color: #bb1318; padding: 10px 18px; border-radius: 4px; text-decoration: none;">
                        Cek Voucher
                    </a></center><br><br>
                    <p style="margin-left: 75%;">
                        Hormat,<br/><br/>
                        <strong>Kop-Aja</strong>
                    </p>';
                    $uti->sendMail($value->email, $value->full_name, $subject, $message_ind, null);
                }
            }
        } elseif ($s == 'private') {
            if (!empty($investors)) {
                foreach ($investors as $key => $value) {
                    $message_ind = 'Hai <b>'.$value->full_name.'</b>,<br>';
                    $message_ind .= 'Ada Cashback sebesar Rp '.number_format($model->amount, 0, ',', '.').' untuk Anda sebagai investor setia Kop-Aja.<br>';
                    $message_ind .= '<img src="https://backend.kop-aja.com/images/voucher/"'.$description_ind->image.' style="width:100%; margin:20px auto;" /><br>';
                    $message_ind .= 'Klik tombol di bawah ini untuk melihat cara untuk mendapatkan cashback ini.<br>';
                    $message_ind .= '<center><a href="https://kop-aja.com/voucher/detail_redeem/'.$model->id.'" style="color: #fff; background-color: #bb1318; padding: 10px 18px; border-radius: 4px; text-decoration: none;">
                        Cek Voucher
                    </a></center><br><br>
                    <p style="margin-left: 75%;">
                        Hormat,<br/><br/>
                        <strong>Kop-Aja</strong>
                    </p>';
                    $uti->sendMail($value->email, $value->full_name, $subject, $message_ind, null);
                }
            }
        }
    }

    public static function kprBookingInterviewEmailNotification($booking_id)
    {
        $model = AptBooking::select(['mst_investor.full_name','mst_investor.email','trc_apt_booking.*'])
            ->join('mst_investor','trc_apt_booking.investor_id','mst_investor.id')
            ->where('trc_kpr_booking.id', $booking_id)
            ->first();
        $subject = "Kami mengundang Anda untuk melengkapi data";
        $msg_ind = "Message indonesia";
        $msg_eng = "Message english";
        $message = $msg_ind.'<br><br>'.$msg_eng;
        $this->sendMail($model->email, $model->full_name, $subject, $message, '');
    }

    /*==================================
    DATE
    ====================================*/
    private function getDateIndonesia($date)
    {
        $date = date('d', strtotime($date));
        $month = '';
        switch (date('m', strtotime($date))) {
            case '01': $month = 'Januari'; break;
            case '02': $month = 'Februari'; break;
            case '03': $month = 'Maret'; break;
            case '04': $month = 'April'; break;
            case '05': $month = 'Mei'; break;
            case '06': $month = 'Juni'; break;
            case '07': $month = 'Juli'; break;
            case '08': $month = 'Agustus'; break;
            case '09': $month = 'September'; break;
            case '10': $month = 'Oktober'; break;
            case '11': $month = 'November'; break;
            case '12': $month = 'Desember'; break;
        }
        $year = date('Y', strtotime($date));
        return $date.' '.$month.' '.$year;
    }

    private function getDateEnglish($date)
    {
        return date('M', strtotime($date)).' '.date('d', strtotime($date)).', '.date('Y', strtotime($date));
    }

    public static function addNotificationN($s='public', $investors=null, $type, $subject, $message, $link)
    {
        if($s == 'public') {
            $investors = MstInvestor::where('active', 1)
                ->where('is_completed', 1)
                ->where('is_deleted', 0)
                ->get();

            if (!empty($investors)) {
                foreach ($investors as $key => $value) {
                    DB::beginTransaction();
                    try {
                        DB::select("call add_notifikasi(?, ?, ?, ?, ?)", [$value->id, $type, $subject, $message, $link]);
                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollback();
                    }
                }
            }
        } elseif ($s == 'private') {
            if (!empty($investors)) {
                foreach ($investors as $key => $value) {
                    DB::beginTransaction();
                    try {
                        DB::select("call add_notifikasi(?, ?, ?, ?, ?)", [$value->id, $type, $subject, $message, $link]);
                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollback();
                    }
                }
            }
        }
    }

    public static function cashbackAppNotification($voucher_id, $device_ids)
    {
        $uti = new Utility;
        $voucher = MstRedeemVoucher::findOrFail($voucher_id);
        if (!empty($device_ids)) {
            $devices = "[";
            $count = count($device_ids);
            $no = 1;
            foreach ($device_ids as $value) {
                $devices .= '"'.$value->device_id.'"';
                $devices .= $no == $count ? ']' : ',';
                $no++;
            }
            
            try {
                $message = '{
                    "title": "Ada cashback Rp '.number_format($voucher->amount, 0 , ',', '.').' untuk Anda!",
                    "body": "Cek voucher Anda untuk melihat cara mendapatkannya"
                }';
                $uti->sendAppNotif($devices, $message);
            } catch (\Throwable $th) {
                // throw new ("Error Processing Request", 500);
            }
        } 
    }

    public static function kprBookingInterviewAppNotification($booking_id)
    {
        $model = AptBooking::select(['mst_investor.full_name','mst_investor.email','mst_investor.device_id','trc_apt_booking.*'])
            ->join('mst_investor','trc_apt_booking.investor_id','mst_investor.id')
            ->where('trc_kpr_booking.id', $booking_id)
            ->first();
        
        if ($model->device_id != '') {
            $device = '["'.$model->device_id.'"]';
            $message = '{
                "title": "Kami mengundang Anda untuk melengkapi data",
                "body": "Silahkan datang membawa berkas pada tanggal 01 Januari 2019 pukul 09.00 WIB"
            }';
            $this->sendAppNotif($device, $message);
        }
    }
}
?>