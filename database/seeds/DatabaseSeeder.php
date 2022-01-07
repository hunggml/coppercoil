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
        // DB::table('users')->insert([
        //     'name'       => 'Admin',
        //     'username'   => 'admin',
        //     'email'      => 'admin@gmail.com',
        //     'password'   => bcrypt('sti@123'),
        //     'avatar'     => 'user.png',
        //     'level'      => 9999,
        //     'created_at' => Carbon::now()->toDateTimeString(),
        //     'updated_at' => Carbon::now()->toDateTimeString(),
        // ]);
    }
}
