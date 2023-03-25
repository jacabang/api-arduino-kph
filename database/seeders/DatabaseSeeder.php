<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_group_id = DB::table('user_group')->insertGetId([
            'user_group' => 'Administrator',
            'access' => '1,2,3,4,5,6,7,8,9',
            'editable' => '3,4,7,8,9',
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $user_id = DB::table('users')->insertGetId([
            'username' => 'admin',
            'email' => 'kurt.andrei23.kas@gmail.com',
            'fullname' => 'Kurt Andrei',
            'password' => bcrypt('123qwe'),
            'created_at' => date("Y-m-d H:i:s"),
            'created_by' => 1
        ]);

        $user_group = DB::table('user_group')
            ->where('id', $user_group_id)
            ->update(['created_by' => 1]);

        $user = DB::table('users')
            ->where('id', $user_id)
            ->update(['user_group_id' => 1]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'Localization',
            'with_editable' => 0,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            // 'deleted_at' => date("Y-m-d H:i:s")
        ]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'Users',
            'parent_id' => $access,
            'with_editable' => 0,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access1 = DB::table('access')->insertGetId([
            'access_name' => 'User',
            'parent_id' => $access,
            'with_editable' => 1,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'User Group',
            'parent_id' => $access,
            'with_editable' => 1,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'Webhooks',
            'parent_id' => NULL,
            'with_editable' => 0,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access_id = DB::table('access')->insertGetId([
            'access_name' => 'Device',
            'parent_id' => NULL,
            'with_editable' => 0,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'List',
            'parent_id' => $access_id,
            'with_editable' => 1,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'Records',
            'parent_id' => $access_id,
            'with_editable' => 1,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $access = DB::table('access')->insertGetId([
            'access_name' => 'Kph',
            'parent_id' => 1,
            'with_editable' => 1,
            'created_by' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
