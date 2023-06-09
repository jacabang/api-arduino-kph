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
use App\Models\Kwph as Kwph;

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

    public static function createUser($data, $image_file){

        return User::create([
            'fullname' => $data['fname'],
            'email' => $data['email'],
            'user_group_id' => $data['user_group_id'],
            'username' => $data['uname'],
            'contact' => $data['contact'],
            'password' => bcrypt('123qwe'),
            'created_by' => Auth::user()->id,
            'image_file' => $image_file,
            ]);
    }

    public static function resetPassword($id){
        $check = self::fetchUserViaId($id);
        $check->password = bcrypt('123qwe');
        $check->save();
    }

    public static function updateUser($data, $img_file, $id){
        $check = self::fetchUserViaId($id);

        if($check != ""):

            $check->fullname = $data['fname'];
            $check->email = $data['email'];
            if(isset($data['contact'])):
                $check->contact = $data['contact'];
            endif;

            if(isset($data['user_group_id'])):
                $check->user_group_id = $data['user_group_id'];
            endif;

            $image_path = public_path()."/uploads/".$check->image_file;
            if($img_file != ""):
                if($check->image_file != ""):
                    if (file_exists($image_path)):
                        unlink($image_path);
                    endif;
                endif;
                $check->image_file = $img_file;
            else:
                if($check->image_file == ""):
                    $check->image_file = $img_file;
                else:
                    if(!file_exists($image_path)):
                        $check->image_file = $img_file;
                    endif;
                endif;
            endif;

            $check->username = $data['uname'];

            $check->save();

        endif;

        return $check;
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
        return DeviceSocket::with('device')
            ->where('id', $socket_id)->first();
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

    public static function fetchKwphLatest(){
        return Kwph::orderBy('created_at','DESC')->first();
    }

    public static function addSocketReading($data){
        $kwph = self::fetchKwphLatest();
        $kwphs = self::fetchKwph();
        $check = self::checkDeviceSocketViaCode($data['socketID']);

        if($check != "" && $kwph != ""):

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

                if(count($check->readings->where('treg', '>', $data['date'])->where('treg', '!=', $data['date'])) != 0):

                        return array(
                            "data" => $check->reading,
                            "response" => 404,
                            "message" => "Has a more ahead record"
                        );
                endif;

                if($check->reading->kwh == $data['watts']):

                    return array(
                            "data" => $check->reading,
                            "response" => 404,
                            "message" => "Duplicate Date Entry"
                        );

                elseif($check->reading->kwh > $data['watts']):
                    return array(
                        "data" => $check->reading,
                        "response" => 404,
                        "message" => "Watts Data is less than the latest record"
                    );
                else:
                    $variance = $data['watts'] - $check->reading->kwh + $check->reading->variance_kwh;
                    $check->reading->kwh = $data['watts'];
                    $check->reading->kwph = $kwph->kwph;
                    $check->reading->variance_kwh = $variance;
                    $check->current_kwh = $data['watts'];

                    if($variance != 0):
                        $check->reading->save();
                        $check->save();

                        return array(
                            "data" => $check->reading,
                            "response" => 404,
                            "message" => 'Reading for '.date('F d, Y', strtotime($check->reading->treg)).' has been updated'
                        );
                    else:
                        return array(
                            "data" => $check,
                            "response" => 404,
                            "message" => "Data Invalid"
                        );
                    endif;
                endif;
                
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

            if($data['date'] < date("Y-m-d", strtotime($kwph->created_at))):
                if(count($kwphs->where('created_at','<=',$data['date'])) == 0):
                    $kwph = Kwph::where(DB::RAW("DATE(created_at)"),'>=', $data['date'])->orderBy('created_at','ASC')->withTrashed()->first();
                else:

                    $kwph = Kwph::where(DB::RAW("DATE(created_at)"),'<=', $data['date'])->orderBy('created_at','ASC')->withTrashed()->first();
                endif;
            endif;

            if($variance != 0):

                $reading = DeviceSocketReading::create([
                    'socket_id' => $check->id,
                    'kwh' => $data['watts'],
                    'kwph' => $kwph->kwph,
                    'variance_kwh' => $variance,
                    'treg' => $data['date']
                    ]);

                $check->current_kwh = $data['watts'];
                $check->save();

            else:
                return array(
                    "data" => [
                        'socket_id' => $check->id,
                        'kwh' => $data['watts'],
                        'kwph' => $kwph->kwph,
                        'variance_kwh' => $variance,
                        'treg' => $data['date']
                    ],
                    "response" => 404,
                    "message" => "Data invalid"
                );
            endif;

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

    public static function fetchSocketIds(){
        return DeviceSocket::where('created_by', Auth::user()->id)->get(['id']);
    }

    public static function fetchLatestReading(){
        $socket_id = self::fetchSocketIds();
        return DeviceSocketReading::select([DB::RAW('MAX(treg) as treg'),'socket_id'])->whereIn('socket_id',$socket_id)
            ->groupBy(['socket_id'])
            ->get();
    }

    public static function fetchReading(){
        $socket_id = self::fetchSocketIds();
        return DeviceSocketReading::orderBy('treg','DESC')->whereIn('socket_id',$socket_id)->get();
    }

    public static function fetchSocketRecordViaId($id){
        return DeviceSocketReading::where('id', $id)->orderBy('treg','DESC')->first();
    }

    public static function fetchKwph(){
        return Kwph::withTrashed()->get();
    }

    public static function fetchKwphNoTrashed(){
        return Kwph::get();
    }

    public static function createKwph($data){
        $check = self::fetchKwphNoTrashed();

        foreach($check as $result):
            $result->delete();
        endforeach;

        return Kwph::create([
            'kwph' => $data['kwph'],
            'created_by' => Auth::user()->id
            ]);
    }

}