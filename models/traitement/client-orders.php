<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

function clientOrderResponse(bool $ok, string $message, array $extra = []): void
{
    echo json_encode(array_merge(['success' => $ok, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$items = $data['items'] ?? [];
$tableId = filter_var($data['table_id'] ?? null, FILTER_VALIDATE_INT);
$type = $data['order_type'] ?? 'surplace';
$notes = trim($data['notes'] ?? '') ?: null;

if (!$tableId || !is_array($items) || !$items || !in_array($type, ['surplace', 'a emporter', 'livraison'], true)) {
    clientOrderResponse(false, 'Table, type de commande ou panier invalide.');
}
if (!fetchOne('SELECT id FROM tables WHERE id=:id AND supprimer=0 AND is_active=1', [':id' => $tableId])) {
    clientOrderResponse(false, 'La table sélectionnée est indisponible.');
}

// Regrouper les lignes identiques empêche de contourner la limite de stock
// en envoyant plusieurs fois le même produit dans la requête.
$requested = [];
foreach ($items as $item) {
    $menuId = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
    $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);
    if (!$menuId || !$quantity || $quantity < 1 || $quantity > 50) {
        clientOrderResponse(false, 'Un article du panier est invalide.');
    }
    $requested[$menuId] = ($requested[$menuId] ?? 0) + $quantity;
    if ($requested[$menuId] > 50) clientOrderResponse(false, 'La quantité demandée est invalide.');
}

global $pdo;
try {
    $pdo->beginTransaction();
    $validated = [];
    $total = 0;

    foreach ($requested as $menuId => $quantity) {
        $menu = fetchOne(
            'SELECT id,nom,price,is_available,stock_quantity FROM menus WHERE id=:id AND supprimer=0 FOR UPDATE',
            [':id' => $menuId]
        );
        if (!$menu || !(int) $menu->is_available) {
            throw new Exception('Le plat demandé n’est plus disponible.');
        }
        if ((int) $menu->stock_quantity <= 0) {
            throw new Exception($menu->nom . ' est en rupture de stock.');
        }
        if ((int) $menu->stock_quantity < $quantity) {
            throw new Exception('Stock insuffisant pour ' . $menu->nom . ' : ' . $menu->stock_quantity . ' unité(s) disponible(s).');
        }
        $validated[] = ['menu' => $menu, 'quantity' => $quantity];
        $total += (float) $menu->price * $quantity;
    }

    $number = 'IS-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
    $orderId = executeInsert(
        'INSERT INTO orders(order_number,table_id,user_id,total_amount,status,payment_status,order_type,special_notes,stock_processed) VALUES(:number,:table,:user,:total,"en attente","en attente",:type,:notes,1)',
        [':number' => $number, ':table' => $tableId, ':user' => $_SESSION['user_id'] ?? null, ':total' => $total, ':type' => $type, ':notes' => $notes]
    );
    if (!$orderId) throw new Exception('Impossible de créer la commande.');

    foreach ($validated as $row) {
        $menu = $row['menu'];
        $quantity = $row['quantity'];
        executeInsert(
            'INSERT INTO order_items(order_id,menu_id,quantity,unit_price) VALUES(:order,:menu,:qty,:price)',
            [':order' => $orderId, ':menu' => $menu->id, ':qty' => $quantity, ':price' => $menu->price]
        );
        executeQuery(
            'UPDATE menus SET stock_quantity=stock_quantity-:qty WHERE id=:id',
            [':qty' => $quantity, ':id' => $menu->id]
        );
        executeInsert(
            "INSERT INTO stock_movements(menu_id,type,quantity,raison) VALUES(:menu,'sortie',:qty,:reason)",
            [':menu' => $menu->id, ':qty' => $quantity, ':reason' => 'Commande ' . $number]
        );
    }

    $pdo->commit();
    clientOrderResponse(true, 'Commande envoyée avec succès.', ['order_number' => $number, 'order_id' => $orderId, 'total' => $total]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    clientOrderResponse(false, $e->getMessage());
}
