<?php
if(session_status()===PHP_SESSION_NONE)session_start();
header('Content-Type: application/json; charset=utf-8');
if(empty($_SESSION['is_logged_in'])||!in_array($_SESSION['user_type']??'', ['admin','gestionnaire'],true)){http_response_code(403);echo json_encode(['success'=>false,'message'=>'Accès non autorisé'],JSON_UNESCAPED_UNICODE);exit;}
require_once __DIR__.'/../../config/database.php';
$data=json_decode(file_get_contents('php://input'),true)?:$_POST;$action=$data['action']??'';$id=filter_var($data['id']??null,FILTER_VALIDATE_INT);$nom=trim($data['nom']??'');
function categoryResponse(bool $ok,string $message):void{echo json_encode(['success'=>$ok,'message'=>$message],JSON_UNESCAPED_UNICODE);exit;}
if(in_array($action,['create','update'],true)){
    if(mb_strlen($nom)<2)categoryResponse(false,'Le nom doit contenir au moins 2 caractères.');
    if(fetchOne('SELECT id FROM categories WHERE LOWER(nom)=LOWER(:nom) AND supprimer=0 AND id<>:id',[':nom'=>$nom,':id'=>$id?:0]))categoryResponse(false,'Cette catégorie existe déjà.');
    if($action==='create'){$new=executeInsert('INSERT INTO categories(nom,supprimer) VALUES(:nom,0)',[':nom'=>$nom]);categoryResponse((bool)$new,$new?'Catégorie créée avec succès.':'Création impossible.');}
    if(!$id)categoryResponse(false,'Catégorie invalide.');$ok=executeQuery('UPDATE categories SET nom=:nom WHERE id=:id AND supprimer=0',[':nom'=>$nom,':id'=>$id]);categoryResponse((bool)$ok,$ok?'Catégorie modifiée avec succès.':'Modification impossible.');
}
if($action==='delete'&&$id){$count=fetchOne('SELECT COUNT(*) total FROM menus WHERE category_id=:id AND supprimer=0',[':id'=>$id]);if(($count->total??0)>0)categoryResponse(false,'Cette catégorie contient encore des plats.');$ok=executeQuery('UPDATE categories SET supprimer=1 WHERE id=:id',[':id'=>$id]);categoryResponse((bool)$ok,$ok?'Catégorie supprimée avec succès.':'Suppression impossible.');}
categoryResponse(false,'Action ou données invalides.');
