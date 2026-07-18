<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['is_logged_in']) || !in_array($_SESSION['user_type'] ?? '', ['admin','gestionnaire'], true)) {
    echo json_encode(['success'=>false,'message'=>'Accès non autorisé'], JSON_UNESCAPED_UNICODE); exit;
}
require_once __DIR__ . '/../../config/database.php';
function menuResponse(bool $ok,string $message):void{echo json_encode(['success'=>$ok,'message'=>$message],JSON_UNESCAPED_UNICODE);exit;}
$data=$_POST ?: (json_decode(file_get_contents('php://input'),true) ?: []);
$action=$data['action']??'';
if($action==='delete'){
    $id=filter_var($data['id']??null,FILTER_VALIDATE_INT); if(!$id)menuResponse(false,'Menu invalide.');
    $ok=executeQuery('UPDATE menus SET supprimer=1 WHERE id=:id',[':id'=>$id]); menuResponse((bool)$ok,$ok?'Menu supprimé avec succès.':'Suppression impossible.');
}
if(!in_array($action,['create','update'],true))menuResponse(false,'Action non reconnue.');
$id=filter_var($data['id']??null,FILTER_VALIDATE_INT);$nom=trim($data['nom']??'');$description=trim($data['description']??'')?:null;
$categoryId=filter_var($data['category_id']??null,FILTER_VALIDATE_INT);$price=filter_var($data['price']??null,FILTER_VALIDATE_FLOAT);
$prep=filter_var($data['preparation_time']??15,FILTER_VALIDATE_INT);$available=!empty($data['is_available'])?1:0;$image=$data['current_image']??null;
if($nom===''||!$categoryId||$price===false||$price<0)menuResponse(false,'Nom, catégorie et prix valide sont requis.');
if(!$prep||$prep<1)$prep=15;if(!fetchOne('SELECT id FROM categories WHERE id=:id AND supprimer=0',[':id'=>$categoryId]))menuResponse(false,'Catégorie invalide.');
if(isset($_FILES['image'])&&$_FILES['image']['error']!==UPLOAD_ERR_NO_FILE){
    if($_FILES['image']['error']!==UPLOAD_ERR_OK||$_FILES['image']['size']>5*1024*1024)menuResponse(false,'Image invalide ou trop volumineuse.');
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES['image']['tmp_name']);$types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if(!isset($types[$mime]))menuResponse(false,'Formats autorisés : JPG, PNG et WEBP.');
    $dir=__DIR__.'/../../assets/uploads/menus';if(!is_dir($dir)&&!mkdir($dir,0775,true))menuResponse(false,"Impossible de créer le dossier d'images.");
    $file=bin2hex(random_bytes(12)).'.'.$types[$mime];if(!move_uploaded_file($_FILES['image']['tmp_name'],$dir.'/'.$file))menuResponse(false,"Impossible d'enregistrer l'image.");$image='assets/uploads/menus/'.$file;
}
$params=[':category'=>$categoryId,':nom'=>$nom,':description'=>$description,':price'=>$price,':image'=>$image,':available'=>$available,':prep'=>$prep];
if($action==='create'){$params[':created_by']=$_SESSION['user_id'];$new=executeInsert('INSERT INTO menus(category_id,nom,description,price,image,is_available,preparation_time,created_by) VALUES(:category,:nom,:description,:price,:image,:available,:prep,:created_by)',$params);menuResponse((bool)$new,$new?'Menu créé avec succès.':'Création impossible.');}
if(!$id)menuResponse(false,'Menu invalide.');$params[':id']=$id;$ok=executeQuery('UPDATE menus SET category_id=:category,nom=:nom,description=:description,price=:price,image=:image,is_available=:available,preparation_time=:prep WHERE id=:id AND supprimer=0',$params);menuResponse((bool)$ok,$ok?'Menu modifié avec succès.':'Modification impossible.');
