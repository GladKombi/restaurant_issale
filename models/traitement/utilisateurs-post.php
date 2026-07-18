<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['is_logged_in']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé'], JSON_UNESCAPED_UNICODE); exit;
}
require_once __DIR__ . '/../../config/database.php';
$json = json_decode(file_get_contents('php://input'), true);
$data = is_array($json) ? $json : $_POST;
$action = $data['action'] ?? '';
$roles = ['admin', 'gestionnaire', 'serveur', 'cuisinier', 'client'];
function respondUser(bool $ok, string $message): void { echo json_encode(['success'=>$ok, 'message'=>$message], JSON_UNESCAPED_UNICODE); exit; }

if ($action === 'delete') {
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) respondUser(false, 'Utilisateur invalide.');
    if ($id === (int) $_SESSION['user_id']) respondUser(false, 'Vous ne pouvez pas supprimer votre propre compte.');
    $ok = executeQuery('UPDATE users SET supprimer=1 WHERE id=:id', [':id'=>$id]);
    respondUser((bool)$ok, $ok ? 'Utilisateur supprimé avec succès.' : 'Suppression impossible.');
}

if (!in_array($action, ['create', 'update'], true)) respondUser(false, 'Action non reconnue.');
$id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
$nom = trim($data['nom'] ?? '');
$postnom = trim($data['postnom'] ?? '');
$email = strtolower(trim($data['email'] ?? ''));
$phone = trim($data['phone'] ?? '') ?: null;
$role = $data['role'] ?? 'client';
$password = (string)($data['password'] ?? '');
if ($nom === '' || $postnom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) respondUser(false, 'Nom, postnom et email valide sont requis.');
if (!in_array($role, $roles, true)) respondUser(false, 'Rôle invalide.');
if (($action === 'create' || $password !== '') && strlen($password) < 4) respondUser(false, 'Le mot de passe doit contenir au moins 4 caractères.');
if (fetchOne('SELECT id FROM users WHERE email=:email AND supprimer=0 AND id<>:id', [':email'=>$email, ':id'=>$id ?: 0])) respondUser(false, 'Cette adresse email est déjà utilisée.');
if ($action === 'create') {
    $newId = executeInsert('INSERT INTO users (nom,postnom,email,password,phone,role) VALUES (:nom,:postnom,:email,:password,:phone,:role)', [':nom'=>$nom, ':postnom'=>$postnom, ':email'=>$email, ':password'=>password_hash($password, PASSWORD_DEFAULT), ':phone'=>$phone, ':role'=>$role]);
    respondUser((bool)$newId, $newId ? 'Utilisateur créé avec succès.' : 'Création impossible.');
}
if (!$id) respondUser(false, 'Utilisateur invalide.');
$params = [':id'=>$id, ':nom'=>$nom, ':postnom'=>$postnom, ':email'=>$email, ':phone'=>$phone, ':role'=>$role];
$sql = 'UPDATE users SET nom=:nom,postnom=:postnom,email=:email,phone=:phone,role=:role';
if ($password !== '') { $sql .= ',password=:password'; $params[':password'] = password_hash($password, PASSWORD_DEFAULT); }
$ok = executeQuery($sql . ' WHERE id=:id AND supprimer=0', $params);
respondUser((bool)$ok, $ok ? 'Utilisateur modifié avec succès.' : 'Modification impossible.');
