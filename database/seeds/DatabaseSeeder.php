<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // for ($i=0; $i < 1000 ; $i++) 
        // { 
        //     $count = count(DB::select("select top 1 Label_ID FROM Import_Materials WHERE IsDelete = 0 and Inventory != 0 and Time_Created > '2021-03-31 00:00:00' GROUP BY Label_ID HAVING COUNT(Label_ID) > 1"));

        //     if ($count == 0) {
        //         break;
        //     } else
        //     {
        //         DB::update("update Import_Materials set IsDelete = 1, Time_Updated = getdate() where ID = (select top 1 ID from import_materials where label_ID in
        //                 (
        //                 SELECT
        //                     top 1 Label_ID
        //                 FROM
        //                     Import_Materials
        //                 WHERE IsDelete = 0 and Inventory != 0 and Time_Created > '2021-03-31 00:00:00'
        //                 GROUP BY
        //                     Label_ID
        //                 HAVING 
        //                     COUNT(Label_ID) > 1) order by ID desc)"
        //         );
        //     }
        // }
        // $data = DB::table('Export_Materials')->get();
        // foreach ($data as $key => $value) 
        // {
        //     $label = DB::table('Label_New')->where('ID', $value->Label_ID)->first();
        //     DB::table('Export_Materials')->where('ID', $value->ID)->update([
        //         'Materials_ID'  => $label->Materials_ID
        //     ]);
        // }
        // $led = array();
        // $count = 0;

        // $up = 0;
        // $st = 26;

        // if (!$up) 
        // {
        //     for ($i = 1 ; $i <= 40 ; $i++) 
        //     {
        //         array_push($led, $i);
        //     }
        // } else
        // {
        //     for ($i = 24; $i >= 13 ; $i--) 
        //     {
        //         array_push($led, $i);
        //     }
        // }
        // // return $led;
        // for ($i=1; $i <= 8 ; $i++) 
        // {
        //     for ($j=1; $j <= 5 ; $j++) 
        //     {
        //         DB::table('Master_Warehouse_Detail')->where('Symbols', 'R'.$st.'-'.$i.'-'.$j)->update([
        //             'Position_Led' => $led[$count]
        //         ]);

        //         $count++;
        //     }
            
        // }

        // // $data = DB::table('Master_Warehouse_Detail')->get();
        // // $data->each(function ($value, $key) 
        // // {
        // //     $group = DB::table('Master_Group_Materials')->where('Symbols', $value->Symbols)->first();
        // //     if ($group) 
        // //     {
        // //         DB::table('Master_Warehouse_Detail')->where('ID', $value->ID)->update([
        // //             'Group_Materials_ID' => $group->ID
        //         ]);
        //     }
            
        // });

        // DB::table('Command_Export_Materials')->truncate();
        // DB::table('Command_Inventories_Materials')->truncate();
        // DB::table('Export_Materials')->truncate();
        // DB::table('Group_Materials')->truncate();
        // DB::table('Import_Materials')->truncate();
        // DB::table('Inventories_Materials')->truncate();
        // DB::table('Kitting_List')->truncate();
        // DB::table('Kitting_List_Detail')->truncate();
        // DB::table('Label_New')->truncate();
        // DB::table('Master_BOM')->truncate();
        // DB::table('Master_Car')->truncate();
        // DB::table('Master_Group_Materials')->truncate();
        // DB::table('Master_Machine')->truncate();
        // DB::table('Master_Materials')->truncate();
        // DB::table('Master_Or_Part')->truncate();
        // DB::table('Master_Packing')->truncate();
        // DB::table('Master_Product')->truncate();
        // DB::table('Master_Supplier')->truncate();
        // DB::table('Master_Type')->truncate();
        // DB::table('Master_Unit')->truncate();
        // DB::table('Master_Warehouse')->truncate();
        // DB::table('Master_Warehouse_Detail')->truncate();
        // DB::table('Production_Detail')->truncate();
        // DB::table('Production_Plan')->truncate();
        // // DB::table('role')->truncate();
        // DB::table('user_role')->truncate();
        // DB::table('users')->truncate();
        // // // User test 
        DB::table('users')->insert([
            'name'       => 'Admin',
            'username'   => 'admin',
            'email'      => 'admin@gmail.com',
            'password'   => bcrypt('sti@123'),
            'avatar'     => 'user.png',
            'level'      => 9999,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('role')->insert(
        [
            'role'       => 'view_master',
            'description'   => 'Xem master data',
            'software'      => 'All',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_master',
            'description'   => 'Th??m master data',
            'software'      => 'All',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'update_master',
            'description'   => 'S???a master data',
            'software'      => 'All',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_master',
            'description'   => 'X??a master data',
            'software'      => 'All',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'import_master',
            'description'   => 'Nh???p file excel',
            'software'      => 'All',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'export_master',
            'description'   => 'Xu???t file excel',
            'software'      => 'All',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_import',
            'description'   => 'Th??m M???i L???nh Nh???p Kho',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_command_export',
            'description'   => 'Th??m M???i L???nh Xu???t Kho',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_command_export',
            'description'   => 'X??a L???nh Xu???t Kho',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_export',
            'description'   => 'Th??m M???i L???nh Xu???t Kho Chi Ti???t',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_export',
            'description'   => 'X??a Xu???t Kho Chi Ti???t',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_inventory',
            'description'   => 'Th??m M???i L??nh Ki???m K??',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_inventory',
            'description'   => 'X??a L???nh Ki???m K??',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'confirm_inventory',
            'description'   => 'X??c Nh???n Ki???m K??',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_plan',
            'description'   => 'Th??m M???i K??? Ho???ch',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_plan',
            'description'   => 'X??a K??? Ho???ch',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'import_plan',
            'description'   => 'Nh???p File Import K??? Ho???ch',
            'software'      => 'Warehouse',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_maintance',
            'description'   => 'Th??m M???i L???nh B???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_maintance',
            'description'   => 'X??a l???nh b???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'extend_maintance',
            'description'   => 'Gia H???n B???o D?????ng Khu??n',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'maintance',
            'description'   => 'Ti???n H??nh B???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'view_mold',
            'description'   => 'Xem Danh S??ch Khu??n',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'detail_maintance',
            'description'   => 'Xem Chi Ti???t B???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'history_maintance',
            'description'   => 'Xem L???ch S??? B???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'view_command_maintance',
            'description'   => 'Xem L???nh B???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
        DB::table('role')->insert([
            'role'       => 'accept_maintance',
            'description'   => 'X??c Nh???n B???o D?????ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'view_repair',
            'description'   => 'Xem L???nh S???a Ch???a',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_repair',
            'description'   => 'Th??m M???i L???nh S???a Ch???a',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'delete_repair',
            'description'   => 'X??a L???nh S???a Ch???a',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'detail_repair',
            'description'   => 'Xem Chi Ti???t S???a Ch???a',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'repair',
            'description'   => 'Ti???n H??nh S???a Ch???a',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'accept_repair',
            'description'   => 'X??c Nh???n S???a Ch???a',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'view_po_mold',
            'description'   => 'Xem ????n H??ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'create_po_mold',
            'description'   => 'Th??m M???i ????n H??ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
         DB::table('role')->insert( [
            'role'       => 'accept_po_mold',
            'description'   => 'X??c Nh???n ????n H??ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'order_po_mold',
            'description'   => '?????t H??ng ',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'success_po_mold',
            'description'   => 'Ho??n Th??nh ????n H??ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
          DB::table('role')->insert([
            'role'       => 'detail_po_mold',
            'description'   => 'Chi Ti???t ????n H??ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
        DB::table('role')->insert([
            'role'       => 'delete_po_mold',
            'description'   => 'H???y ????n H??ng',
            'software'      => 'Maintance',
            'time_created' => Carbon::now()->toDateTimeString(),
            'time_updated' => Carbon::now()->toDateTimeString(),
            'isdelete'=>0
        ]);
    }
}
