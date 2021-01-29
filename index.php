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


$app->run();