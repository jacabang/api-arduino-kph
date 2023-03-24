<?php
namespace App\Repositories;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\User as User;
use App\Models\UserGroup as UserGroup;
use App\Models\Device as Device;
use App\Models\DeviceSocket as DeviceSocket;
use App\Models\DeviceSocketReading as DeviceSocketReading;

class HomergyRepositories
{
    protected $db;
    protected $timestamp = false;

    public function __construct(Connection $db)
    {
        $this->db = $db;
        date_default_timezone_set('Asia/Manila');
    }

    public static function menu(){

        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));

        return view('partials.menu', compact('access'));
    }

    public static function menu2(){

        // $user_group = Auth::user()->user_group;
        // $access = array_flip(explode(",", $user_group->access));

        return view('partials.menu2'
          // , 
          // compact('access')
        );
    }

    public static function fetchUserViaUserName($username){
        return User::where('username', $username)
            ->first();
    }

    public static function fetchUsers(){
        return User::get();
    }

    public static function fetchUserGroup(){
        return UserGroup::get();
    }

    public static function fetchUserGroupViaId($id){
        return UserGroup::where('id', $id)->first();
    }

    public static function fetchAccess(){
        return DB::select("
            SELECT b.id as id, a.access_name, CONCAT(a.access_name,' > ',b.access_name) as label, b.with_editable
            FROM
            (SELECT id, access_name, parent_id, with_editable FROM access WHERE deleted_at IS NULL) as a
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable FROM access WHERE deleted_at IS NULL) as b
            ON
            a.id = b.parent_id
            WHERE a.parent_id IS NULL
            UNION ALL
            SELECT id, access_name, access_name as label, with_editable  FROM access
            WHERE parent_id IS NULL AND deleted_at IS NULL
            UNION ALL
            SELECT b.id as id, a.access_name, CONCAT(c.access_name,' > ',a.access_name,' > ',
            b.access_name) as label, b.with_editable
            FROM
            (SELECT id, access_name, parent_id, with_editable FROM access WHERE deleted_at IS NULL) as a
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable FROM access WHERE deleted_at IS NULL) as b
            ON
            a.id = b.parent_id
            INNER JOIN
            (SELECT * FROM access WHERE deleted_at IS NULL) as c
            ON c.id = a.parent_id
            WHERE c.parent_id IS NULL
            UNION ALL
            SELECT b.id as id, a.access_name, CONCAT(d.access_name,' > ',c.access_name,' > ',a.access_name,' > ',b.access_name) as label, b.with_editable
            FROM
            (SELECT id, access_name, parent_id, with_editable FROM access WHERE deleted_at IS NULL) as a
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable FROM access WHERE deleted_at IS NULL) as b
            ON
            a.id = b.parent_id
            INNER JOIN
            (SELECT * FROM access WHERE deleted_at IS NULL) as c
            ON c.id = a.parent_id
            INNER JOIN
            (SELECT * FROM access WHERE deleted_at IS NULL) as d
            ON d.id = c.parent_id
            WHERE d.parent_id IS NULL
            UNION ALL
            SELECT b.id as id, a.access_name, CONCAT(e.access_name,' > ', d.access_name,' > ',c.access_name,' > ',a.access_name,' > ',b.access_name) as label, b.with_editable
            FROM
            (SELECT id, access_name, parent_id, with_editable  FROM access WHERE deleted_at IS NULL) as a
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable  FROM access WHERE deleted_at IS NULL) as b
            ON
            a.id = b.parent_id
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable  FROM access WHERE deleted_at IS NULL) as c
            ON c.id = a.parent_id
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable  FROM access WHERE deleted_at IS NULL) as d
            ON d.id = c.parent_id
            INNER JOIN
            (SELECT id, access_name, parent_id, with_editable  FROM access WHERE deleted_at IS NULL) as e
            ON e.id = d.parent_id
            ORDER BY label, id
        ");
    }

    public static function fetchUserViaId($id){
        return User::where('id', $id)->first();
    }

    public static function fetchDevice(){
        return Device::get();
    }

    public static function fetchDeviceViaId($id){

        return Device::with('socket','socket.readings')
            ->where('id', $id)->first();

    }

    public static function addDevice($data){

        // $device = self::fetchDeviceViaId(1);
        $device = Device::create([
            'device_name' => $data['device_name'],
            'device_code' => $data['device_code'],
            'created_by' => Auth::user()->id,
        ]);

       self::updateDeviceSocket($data, $device->id);
    }

    public static function updateDeviceSocket($data, $device_id){

        $check = self::fetchMissingDeviceSocket($data['id'], $device_id);

        foreach($check as $result):
            $result->socket_name .= " (DELETED)";
            $result->socket_code .= " (DELETED)";
            $result->save();
            $result->delete();
        endforeach;

        for($x = 0; $x < count($data['id']); $x++):
            $socket_id = $data['id'][$x];
            $socket_name = $data['socket_name'][$x];
            $socket_code = $data['system'][$x];
            $current_kwh = 0;

            if($socket_id == 0):
                self::addDeviceSocket($socket_code, $socket_name, $current_kwh, $device_id);
            else:
                $socket = self::fetchDeviceSocketViaId($socket_id);

                if($socket != ""):
                    $socket->socket_name = $socket_name;
                    $socket->socket_code = $socket_code;
                    $socket->save();
                endif; 
            endif;
        endfor;
    }

    public static function fetchDeviceSocketViaId($socket_id){
        return DeviceSocket::where('id', $socket_id)->first();
    }

    public static function addDeviceSocket($socket_code, $socket_name, $current_kwh, $device_id){

        return DeviceSocket::create([
            'device_id' => $device_id,
            'socket_name' => $socket_name,
            'socket_code' => $socket_code,
            'current_kwh' => $current_kwh,
            'created_by' => Auth::user()->id,
            ]);
    }

    public static function fetchMissingDeviceSocket($ids, $device_id){
        return DeviceSocket::whereNotIn('id', $ids)
            ->where('device_id', $device_id)
            ->get();
    }

    public static function updateDevice($data, $id){

        $check = self::fetchDeviceViaId($id);

        if($check != ""):
            $check->device_name = $data['device_name'];
            $check->save();
            self::updateDeviceSocket($data, $check->id);
        endif;
    }

    public static function fetchAvailableSocket(){
        return DeviceSocket::with('device')->get();
    }

    public static function addUserGroup($data){
        $access = NULL;

        if(isset($data['list'])):
            $access = implode(",", $data['list']);
        endif;

        $editable = NULL;

        if(isset($data['list1'])):
            $editable = implode(",", $data['list1']);
        endif;

        return UserGroup::create([
            'user_group' => $data['user_group'],
            'editable' => $editable,
            'access' => $access,
            'created_by' => Auth::user()->id,
            ]);
    }

    public static function updateUserGroup($data, $id){
        $check = self::fetchUserGroupViaId($id);

        if($check != ""):
            $access = NULL;

            if(isset($data['list'])):
                $access = implode(",", $data['list']);
            endif;

            $editable = NULL;

            if(isset($data['list1'])):
                $editable = implode(",", $data['list1']);
            endif;

            $check->user_group = $data['user_group'];
            $check->access = $access;
            $check->editable = $editable;
            $check->save();


        endif;
    }

    public static function checkDeviceSocketViaCode($code){
        return DeviceSocket::with('readings')->where('socket_code', $code)->first();
    }

    public static function addSocketReading($data){
        $check = self::checkDeviceSocketViaCode($data['socketID']);

        if($check != ""):

            $last_reading = 0;

            if(count($check->readings) != 0):
                $last_reading = $check->reading->kwh;

                if($last_reading > $data['watts']):
                    return array(
                        "data" => $check,
                        "response" => 404,
                        "message" => "Watts Data is less than the latest record"
                    );
                endif;
            endif;

            //check if there is a data same date

            if(count($check->readings->where('treg', $data['date'])) != 0):

                foreach($check->readings->where('treg', $data['date']) as $result):
                    return array(
                            "data" => $result,
                            "response" => 404,
                            "message" => "Duplicate Date Entry"
                        );

                endforeach;
            else:

                if($check->reading != ""):

                    $date1 = $check->reading->treg;
                    $date2 = $data['date'];

                    $sql = "SELECT TIMESTAMPDIFF(DAY, '{$date1}', '{$date2}') as apart";

                    $query = DB::select($sql);

                    foreach($query as $result1):
                        if($result1->apart < 0):
                            return array(
                                "data" => $check,
                                "response" => 404,
                                "message" => "Date is less than the date latest record"
                            );
                        endif;
                    endforeach;

                        // $startTime = Carbon::parse($check->reading->treg);
                        // $finishTime = Carbon::parse($data['date']);

                        // return $totalDuration = $finishTime->diffInDays($startTime);
                endif;
            endif;

            $variance = $data['watts'] - $last_reading;

            $reading = DeviceSocketReading::create([
                'socket_id' => $check->id,
                'kwh' => $data['watts'],
                'variance_kwh' => $variance,
                'treg' => $data['date']
                ]);

            $check->current_kwh = $data['watts'];
            $check->save();

            return array(
                "data" => $reading,
                "response" => 202,
                "message" => "Successfully Add Reading"
            );


        else:

            return array(
                "data" => NULL,
                "response" => 404,
                "message" => "Socket Not Exist"
            );

        endif;
    }

}