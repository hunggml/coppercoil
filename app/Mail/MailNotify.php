<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Swift_Attachment;
use Illuminate\Support\Facades\Mail;
use App\Models\WarehouseSystem\CommandImport;
use App\Models\WarehouseSystem\ExportMaterials;
class MailNotify 
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('welcome');
        //here is your blade view name
    }
    public function send_mail($email,$num,$mater,$Go,$To,$Quantity,$Count)
    {
        // dd('run');
        $user =  User::where('IsDelete',0)->get();
        Mail::send('mail',['num' =>$num,'mater'=>$mater,'go'=>$Go,'to'=>$To,'quan'=>$Quantity,'count'=>$Count],
            function($massage) use ($email){
                $massage->to($email,'hung')
                ->from('hethongkhojkv@gmail.com','Admin')
                ->setSubject('Thông Báo Duyệt Xuất Kho');
            });





    }

    public function send_mail1()
    {
        $export = ExportMaterials::where('IsDelete',0)
        ->where('Status','>=',2)
        ->where('Status','<=',3)
        ->get();
        $arr1 = [];
        $arr2 = [];
        foreach($export as $value)
        {
            $a = $value->Count ? $value->Count : $value->Quantity; 
            $b = $value->Count ? (count(collect($value->detail)->where('Status',1))) : floatval(collect($value->detail->where('Status',1))->sum('Quantity')); 
            $c = $value->Count ? (count(collect($value->detail)->where('Transfer',1))) : floatval(collect($value->detail->where('Transfer',1))->sum('Quantity')); 
            if($a >$b)
            {
                array_push($arr1,$value);
            }
            else
            {
                if($value->Go != $value->To)
                {
                    if($a >$c)
                    {
                        array_push($arr2,$value);
                    }
                }
            }
        }


        $import =  CommandImport::where('IsDelete',0)
        ->get();

        $arr3 = [];
        
        foreach($import as $value1)
        {
            $a = count(collect($value1->detail)->where('Status','!=',2)); 
            $b = count(collect($value1->detail)->where('Status',1));
            
            if($a > $b)
            {
                // dd($a,$b);
                array_push($arr3,$value1);
            }
        }
        // dd($arr1,$arr2,$arr3);
        Mail::send('mail1',[],
            function($massage){
                $massage->to('dvhungg1@gmail.com','hung')
                ->from('hethongkhojkv@gmail.com','Admin')
                ->setSubject('Mail Cảnh Báo Danh Sách Lệnh Chưa Hoàn Thành');
            });
    }

    public function send_mail2($email2,$num1,$num2,$mater,$Go,$To,$command)
    {

        $user =  User::where('IsDelete',0)->get();
        Mail::send('mail2',['num1' =>$num1,'num2'=>$num2,'mater'=>$mater,'go'=>$Go,'to'=>$To,'command'=>$command],
            function($massage) use ($email2){
                $massage->to($email2,'hung')
                ->from('hethongkhojkv@gmail.com','Admin')
                ->setSubject('Mail Báo Cáo Chênh Lệch Chuyển Kho');
            });




    }
}