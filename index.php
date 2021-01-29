<?php 
require __DIR__ . '/vendor/autoload.php';
require 'libs/NotORM.php'; 
use \Slim\App;
$app = new App();
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'dbcatatan';
$dbmethod = 'mysql:dbname=';
$dsn = $dbmethod.$dbname;
$pdo = new PDO($dsn, $dbuser, $dbpass);
$db  = new NotORM($pdo);
$app-> get('/', function(){
    echo "Saya Sedang Belajar API";
});

$app->post('/addcatatan', function($request, $response, $args) use($app, $db){
    $param = $request->getParams();
    $r = $db->tb_catatan->insert($param);
    $res["error"] = false;
    $res["message"] = "Berhasil menambahkan catatan ke database";
    echo json_encode($res);
});


$app ->get('/listcatatan', function() use($app, $db){
    $res["error"] = false;
       $res["message"] = "Berhasil mendapatkan data Catatan";
       foreach($db->v_catatan()->where('status', 0) as $data){
           $res['data'][] = array(
               'id' => $data['id'],
               'judul' => $data['judul'],
               'catatan' => $data['catatan'],
               'create_at' => $data['create_at'],
               'update_at' => $data['update_at']
               );
       }
       echo json_encode($res);
   });

   $app->post('/editcatatan/{id}', function($request, $response, $args) use($app, $db){
    $q = $db->tb_catatan()->where('id', $args);
    if($q->fetch()){
        $param = $request->getParams();
        $r = $q->update($param);
        echo json_encode(array(
            "error" => false,
            "message" => "Catatan berhasil diupdate"));
    }else{
        echo json_encode(array(
            "error" => true,
            "message" => "Catatan tersebut tidak ada"));
    }
});

$app->post('/deletecatatan/{id}', function($request, $response, $args) use($app, $db){
    $q = $db->tb_catatan()->where('id', $args);
    if($q->fetch()){
        $param = array("status" => 1);
        $r = $q->update($param);
        echo json_encode(array(
            "error" => false,
            "message" => "Catatan berhasil di delete"));
    }else{
        echo json_encode(array(
            "error" => true,
            "message" => "Catatan tersebut tidak ada"));
    }
});
$app->post('/restorecatatan/{id}', function($request, $response, $args) use($app, $db){
    $q = $db->tb_catatan()->where('id', $args);
    if($q->fetch()){
        $param = array("status" => 0);
        $r = $q->update($param);
        echo json_encode(array(
            "error" => false,
            "message" => "Catatan berhasil di restore"));
    }else{
        echo json_encode(array(
            "error" => true,
            "message" => "Catatan tersebut tidak ada"));
    }
});
$app ->get('/logcatatan', function() use($app, $db){
 $res["error"] = false;
    $res["message"] = "Berhasil mendapatkan log Catatan";
    foreach($db->v_catatan_log() as $data){
        $res['data'][] = array(
            'id' => $data['id'],
            'id_catatan' => $data['id_catatan'],
            'method' => $data['method'],
            'judul' => $data['judul'],
            'catatan' => $data['catatan'],
            'update_at' => $data['update_at']
            );
    }
    echo json_encode($res);
});


$app->run();