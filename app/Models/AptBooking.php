<?php

namespace App\Models;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AptBooking extends Model
{
    protected $table = 'trc_apt_booking';
    protected $fillable = [
        'investor_id',
        'code_unit',
        'booking_date',
        'price',
        'tenor',
        'installment',
        'maintenance',
        'status',
        'no_contract',
        'notes',
        'approved_at',
        'approved_by',
        'canceled_at',
        'canceled_by_admin',
        'canceled_by_investor',
        'rejected_by',
        'rejected_at',
        'set_up_date',
        'place',
        'interview_by',
        'interview_at'
    ];
    public $timestamps = false;

    protected $listStatus = [
        'new' => ['name' => 'NEW', 'label'=>'label bg-aqua'],
        'interview' => ['name' => 'INTERVIEW', 'label'=>'label label-warning'],
        'approved' => ['name' => 'APPROVED', 'label'=>'label label-success'],
        'rejected' => ['name' => 'REJECTED', 'label'=>'label label-danger'],
        'canceled' => ['name' => 'CANCELED', 'label'=>'label bg-purple'],
        'canceledadm' => ['name' => 'CANCELED BY ADMIN', 'label'=>'label bg-teal'],
    ];

    public function statusLabeled()
    {
        $data = $this->listStatus[strtolower($this->status)];
        return '<span class="'.$data['label'].'">'.$data['name'].'</span>';
    }

    public function investor()
    {
        return $this->belongsTo('App\Models\MstInvestor');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\MstUnitModels', 'code_unit');
    }

    // Notifikasi
    private function sendMail($email, $name, $subject, $message, $message_txt)
    {
        $model = new Utility;
        $model->sendMail($email, $name, $subject, $message, $message_txt);
    }

    private function sendNotif($device, $message)
    {
        $model = new Utility;
        $model->sendAppNotif($device, $message);
    }

    public function emailNotifInterview($id)
    {
        $model      = AptBooking::find($id);
        $full_name  = $model->investor->full_name;
        $email      = $model->investor->email;
        $subject    = 'Kami mengundang Anda untuk melengkapi data - Kop-Aja';
        $message    = 'Hai '.$full_name.'<br>
        Kami mengundang anda datang ke kantor kami di pada :<br>
        <table border="0" cellpadding="10px">
            <tr>
                <th width="20%">Hari</th>
                <td>: </td>
            </tr>
            <tr>
                <th>Jam</th>
                <td>: </td>
            </tr>
            <tr>
                <th>Tempat</th>
                <td>: PT INTI ARTHA MULTIFINANCE  Grand Slipi Tower Lt. 11 Unit FGH, Jl. S Parman Kav. 21-24 Jakarta Barat 11480</td>
            </tr>
        </table><br>
        Untuk melengkapi berkas yang aslinya seperti KTP Suami dan Istri, KK, Slip Gaji,NPWP, kartu pegawai, Tabungan/rekening koran 3 bulan terakhir, Surat Izin Usaha.
        ';
        $this->sendMail($email, $full_name, $subject, $message, '');
    }

    public function notifInterview($id)
    {
        $model      = AptBooking::find($id);
        if ($model->investor->device_id != '') {
            $device = '["'.$model->investor->device_id.'"]';
            $message = '{
                "title": "Kami mengundang Anda untuk melengkapi data",
                "body": "Silahkan datang membawa berkas pada tanggal '.date('d M Y', strtotime($model->set_up_date)).' pukul '.date('H:i', strtotime($model->set_up_date)).' WIB"
            }';
            $this->sendNotif($device, $message);
        }
    }

    public function emailNotifApprove($id)
    {
        $model      = AptBooking::find($id);
        $full_name  = $model->investor->full_name;
        $email      = $model->investor->email;
        $apartment  = $model->unit->floor->apt->name;
        $floor      = $model->unit->floor->name;
        $unit       = $model->unit->name;
        $subject    = 'Pengajuan KPR Anda Telah Disetujui';
        $message    = 'Dear Bapak/Ibu '.$full_name.'<br>';
        $message    .= 'Setelah melalui proses interview, Kami KOPAJA <u>MENYETUJUI</u> pengajuan KPR Bapak/Ibu pada unit :<br>
        <table cellpadding="10">
            <tbody>
                <tr>
                    <th>Nama Apartemen</th>
                    <td>'.$apartment.'</td>
                </tr>
                <tr>
                    <th>Lantai</th>
                    <td>'.$floor.'</td>
                </tr>
                <tr>
                    <th>Nomor Unit</th>
                    <td>'.$unit.'</td>
                </tr>
            </tbody>
        </table>';
        $model->sendMail($email, $full_name, $subject, $message, '');
    }

    public function notifApprove($id)
    {
        $model      = AptBooking::find($id);
        if ($model->investor->device_id != '') {
            $device = '["'.$model->investor->device_id.'"]';
            $message = '{
                "title": "Pengajuan KPR Anda Telah Disetujui",
                "body": "Segera lakukan melengkapi berkas dan pembayaran"
            }';
            $model->sendNotif($device, $message);
        }
    }

    public function emailNotifCancel($id)
    {
        $model      = AptBooking::find($id);
        $full_name  = $model->investor->full_name;
        $email      = $model->investor->email;
        $apartment  = $model->unit->floor->apt->name;
        $floor      = $model->unit->floor->name;
        $unit       = $model->unit->name;
        $subject    = 'Pengajuan KPR Anda Telah Dibatalkan - Kop-Aja';
        $message    = 'Dear Bapak/Ibu '.$full_name.'<br>';
        $message    .= 'Karena alasan satu dan lain hal, KOPAJA <u>MEMBATALKAN</u> pengajuan KPR Bapak/Ibu pada unit :<br>
        <table cellpadding="10">
            <tbody>
                <tr>
                    <th>Nama Apartemen</th>
                    <td>'.$apartment.'</td>
                </tr>
                <tr>
                    <th>Lantai</th>
                    <td>'.$floor.'</td>
                </tr>
                <tr>
                    <th>Nomor Unit</th>
                    <td>'.$unit.'</td>
                </tr>
            </tbody>
        </table>';
        $model->sendMail($email, $full_name, $subject, $message, '');
    }

    public function notifCancel($id)
    {
        $model      = AptBooking::find($id);
        if ($model->investor->device_id != '') {
            $device = '["'.$model->investor->device_id.'"]';
            $message = '{
                "title": "Pengajuan KPR Anda Telah Kami Batalkan",
                "body": "Maaf, karena alasan tertentu, pengajuan KPR Anda telah kami batalkan."
            }';
            $model->sendNotif($device, $message);
        }
    }

    public function emailNotifReject($id)
    {
        $model      = AptBooking::find($id);
        $full_name  = $model->investor->full_name;
        $email      = $model->investor->email;
        $apartment  = $model->unit->floor->apt->name;
        $floor      = $model->unit->floor->name;
        $unit       = $model->unit->name;
        $subject    = 'Pengajuan KPR Anda Kami Tolak';
        $message    = 'Dear Bapak/Ibu '.$full_name.'<br>';
        $message    .= 'Setelah melalui proses interview, Kami KOPAJA <u>TIDAK MENYETUJUI</u> pengajuan KPR Bapak/Ibu pada unit :<br>
        <table cellpadding="10">
            <tbody>
                <tr>
                    <th>Nama Apartemen</th>
                    <td>'.$apartment.'</td>
                </tr>
                <tr>
                    <th>Lantai</th>
                    <td>'.$floor.'</td>
                </tr>
                <tr>
                    <th>Nomor Unit</th>
                    <td>'.$unit.'</td>
                </tr>
            </tbody>
        </table>';
        $model->sendMail($email, $full_name, $subject, $message, '');
    }

    public function notifReject($id)
    {
        $model      = AptBooking::find($id);
        if ($model->investor->device_id != '') {
            $device = '["'.$model->investor->device_id.'"]';
            $message = '{
                "title": "Pengajuan KPR Anda Ditolak",
                "body": "Maaf, pengajuan KPR Anda kami tolak"
            }';
            $model->sendNotif($device, $message);
        }
    }
}
