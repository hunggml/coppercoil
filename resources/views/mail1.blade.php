<?php 

$export = App\Models\WarehouseSystem\ExportMaterials::where('IsDelete',0)
->where('Status','>=',2)
->where('Status','<',3)
->get();
// dd($export);
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


$import =  App\Models\WarehouseSystem\CommandImport::where('IsDelete',0)
->get();

$arr3 = [];
foreach($import as $value1)
{
     $a = count(collect($value1->detail)->where('Status','!=',2)); 
     $b = count(collect($value1->detail)->where('Status',1));; 
     if($a > $b)
     {
        array_push($arr3,$value1);
     }
}
?>


<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" class="js-focus-visible" data-js-focus-visible=""><head>
    <title>
    </title>
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
      #outlook a {
        padding: 0;
      }

      body {
        margin: 0;
        padding: 0;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
      }

      table,
      td {
        border-collapse: collapse;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      p {
        display: block;
        margin: 13px 0;
      }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Nunito:normal,italic,bold&amp;display=swap" rel="stylesheet" type="text/css">
    <style type="text/css">
      @import url(https://fonts.googleapis.com/css?family=Nunito:normal,italic,bold&display=swap);
    </style>
    <style type="text/css">
      @media only screen and (min-width:800px) {
        .mj-column-per-100 {
          width: 100% !important;
          max-width: 100%;
        }

        .mj-column-per-33-333333333333336 {
          width: 80.333333333333336% !important;
          max-width: 80.333333333333336%;
        }
      }
    </style>
    <style media="screen and (min-width:480px)">
      .moz-text-html .mj-column-per-100 {
        width: 100% !important;
        max-width: 100%;
      }

      .moz-text-html .mj-column-per-33-333333333333336 {
        width: 80.333333333333336% !important;
        max-width: 80.333333333333336%;
      }
    </style>
    <style type="text/css">
      @media only screen and (max-width:800px) {
        table.mj-full-width-mobile {
          width: 100% !important;
        }

        td.mj-full-width-mobile {
          width: auto !important;
        }
      }
    </style>
  <style type="text/css">
@font-face {
  font-weight: 400;
  font-style:  normal;
  font-family: 'Circular-Loom';

  src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Book-cd7d2bcec649b1243839a15d5eb8f0a3.woff2') format('woff2');
}

@font-face {
  font-weight: 500;
  font-style:  normal;
  font-family: 'Circular-Loom';

  src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Medium-d74eac43c78bd5852478998ce63dceb3.woff2') format('woff2');
}

@font-face {
  font-weight: 700;
  font-style:  normal;
  font-family: 'Circular-Loom';

  src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Bold-83b8ceaf77f49c7cffa44107561909e4.woff2') format('woff2');
}

@font-face {
  font-weight: 900;
  font-style:  normal;
  font-family: 'Circular-Loom';

  src: url('https://cdn.loom.com/assets/fonts/circular/CircularXXWeb-Black-bf067ecb8aa777ceb6df7d72226febca.woff2') format('woff2');
}
</style></head>
  <body style="word-spacing:normal;background-color:#EEEDEA;">
    <div style="background-color:#EEEDEA;">
      <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
      <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:1000px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
          <tbody>
            <tr>
              <td style="border-bottom:none;border-left:none;border-right:none;border-top:10px solid #66b3ff;direction:ltr;font-size:0px;padding:40px;padding-bottom:0px;padding-left:40px;padding-right:40px;padding-top:20px;text-align:center;">
                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:520px;" ><![endif]-->
                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                    <tbody>
                      <tr>
                        <td style="vertical-align:top;padding:0;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
                            <tbody>
                              <tr>
                                <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                                  <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;line-height:1;text-align:center;color:#66b3ff;">STI-MES</div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:5px;line-height:5px;"> </div>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" style="font-size:0px;padding:10px 0;padding-top:10px;padding-right:0;padding-bottom:10px;padding-left:0;word-break:break-word;">
                                  <p style="border-top:solid 2px #66b3ff;font-size:1px;margin:0px auto;width:100%;">
                                  </p>
                                </td>   
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:30px;line-height:30px;"> </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      @foreach($arr1 as $value)
      <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;border-radius:0px 0px 10px 10px;max-width:1000px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;border-radius:0px 0px 10px 10px;">
          <tbody>
            <tr>
              
              <td style="direction:ltr;font-size:0px;padding:40px;padding-bottom:0px;padding-left:50px;padding-right:50px;padding-top:0px;text-align:center;">
                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                    <tbody>
                      <tr>
                        <td style="vertical-align:top;padding:0;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
                            <thead>
                              <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                                <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;line-height:1.25;text-align:center;color:#231F20;"><strong>Lệnh {{$value->Type == 0 ? __('PM') : ($value->Type == 1 ? __('PDA') : __('HT')  ) }}-{{date_format(date_create($value->Time_Created),"YmdHis")}} Xuất Nguyên Vật Liệu Chưa Hoàn Thành Xuất Kho</strong></div>
                              </td>
                            </thead>
                            <tbody>
                              <tr>
                                <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                                  <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:24px;line-height:1.5;text-align:center;color:#231F20;">
                                    <span style="word-spacing: normal;">{{$value->materials ? $value->materials->Symbols : ''}}</span>
                                    <br>
                                    <span style="word-spacing: normal;">{{$value->go ? $value->go->Symbols : ''}} Đến {{$value->to ? $value->to->Symbols :''}} </span>
                                    <br>
                                    <span style="word-spacing: normal;">{{$value->Count ? $value->Count : floatval($value->Quantity) }} {{$value->Count ? 'box' : 'kg' }}</span>
                                    <br>
                                </div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:50px;line-height:50px;"> </div>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" vertical-align="middle" style="font-size:0px;padding:0px;word-break:break-word;">
                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;width:100%;line-height:100%;">
                                    <tbody><tr>
                                        <td align="center" bgcolor="#66b3ff" role="presentation" style="border:none;border-radius:10px;cursor:auto;mso-padding-alt:10px 25px;background:#66b3ff;" valign="middle">
                                            <a href="{{route('warehousesystem.export.detail',['ID'=>$value->ID])}}">
                                            <p style="display:inline-block;background:#66b3ff;color:#ffffff;font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;font-weight:normal;line-height:1.75;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:10px;">
                                              <strong>Thông Tin</strong><br>
                                            </p>
                                            </a>
                                        </td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:10px;line-height:10px;"> </div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:10px;line-height:10px;"> </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      @endforeach


      @foreach($arr2 as $value)
      <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;border-radius:0px 0px 10px 10px;max-width:1000px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;border-radius:0px 0px 10px 10px;">
          <tbody>
            <tr>
              
              <td style="direction:ltr;font-size:0px;padding:40px;padding-bottom:0px;padding-left:50px;padding-right:50px;padding-top:0px;text-align:center;">
                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                    <thead>
                      <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                        <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;line-height:1.25;text-align:center;color:#231F20;"><strong>Lệnh {{$value->Type == 0 ? __('PM') : ($value->Type == 1 ? __('PDA') : __('HT')  ) }}-{{date_format(date_create($value->Time_Created),"YmdHis")}} Chưa Hoàn Thành Chuyển Kho</strong></div>
                      </td>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="vertical-align:top;padding:0;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
                            <tbody>
                              <tr>
                                <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                                  <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:24px;line-height:1.5;text-align:center;color:#231F20;">
                                    <span style="word-spacing: normal;">Nguyên Vật Liệu : {{$value->materials ? $value->materials->Symbols : ''}}</span>
                                    <br>
                                    <span style="word-spacing: normal;">Kho Xuất : {{$value->go ? $value->go->Symbols : ''}} </span>
                                    <br>
                                    <span style="word-spacing: normal;">Kho Nhập : {{$value->to ? $value->to->Symbols :''}} </span>
                                    <br>
                                    <span style="word-spacing: normal;">{{$value->Count ? $value->Count : $value->Quantity }} {{$value->Count ? 'box' : 'kg' }}</span>
                                    <br>
                                </div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:50px;line-height:50px;"> </div>
                                </td>
                              </tr> 
                              <tr>
                                <td align="center" vertical-align="middle" style="font-size:0px;padding:0px;word-break:break-word;">
                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;width:100%;line-height:100%;">
                                    <tbody><tr>
                                        <td align="center" bgcolor="#66b3ff" role="presentation" style="border:none;border-radius:10px;cursor:auto;mso-padding-alt:10px 25px;background:#66b3ff;" valign="middle">
                                            <a href="{{route('warehousesystem.export')}}">
                                            <p style="display:inline-block;background:#66b3ff;color:#ffffff;font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;font-weight:normal;line-height:1.75;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:10px;">
                                              <strong>Thông Tin</strong><br>
                                            </p>
                                            </a>
                                        </td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:10px;line-height:10px;"> </div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:10px;line-height:10px;"> </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      @endforeach

      @foreach($arr3 as $value)
      <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;border-radius:0px 0px 10px 10px;max-width:1000px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;border-radius:0px 0px 10px 10px;">
          <tbody>
            <tr>
              
              <td style="direction:ltr;font-size:0px;padding:40px;padding-bottom:0px;padding-left:50px;padding-right:50px;padding-top:0px;text-align:center;">
                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                    <thead>
                      <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                        <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;line-height:1.25;text-align:center;color:#231F20;"><strong>Lệnh {{$value->Name}} Chưa Hoàn Thành Nhập Kho</strong></div>
                      </td>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="vertical-align:top;padding:0;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
                            <tbody>
                              <tr>
                                <td align="center" style="font-size:0px;padding:10px;word-break:break-word;">
                                  <div style="font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:24px;line-height:1.5;text-align:center;color:#231F20;">
                                </div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:50px;line-height:50px;"> </div>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" vertical-align="middle" style="font-size:0px;padding:0px;word-break:break-word;">
                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;width:100%;line-height:100%;">
                                    <tbody><tr>
                                        <td align="center" bgcolor="#66b3ff" role="presentation" style="border:none;border-radius:10px;cursor:auto;mso-padding-alt:10px 25px;background:#66b3ff;" valign="middle">
                                            <a href="{{route('warehousesystem.import.detail',['ID'=>$value->ID])}}">
                                            <p style="display:inline-block;background:#66b3ff;color:#ffffff;font-family:'Nunito', 'Helvetica', 'Arial', sans-serif;font-size:20px;font-weight:normal;line-height:1.75;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:10px;">
                                              <strong>Thông Tin</strong><br>
                                            </p>
                                            </a>
                                        </td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:10px;line-height:10px;"> </div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-size:0px;word-break:break-word;">
                                  <div style="height:10px;line-height:10px;"> </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      @endforeach
     