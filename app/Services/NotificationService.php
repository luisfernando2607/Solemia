<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyUser(
        $userId,
        string $type,
        string $title,
        ?string $message = null,
        ?string $link = null,
        ?array $data = null,
        ?int $createdBy = null,
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'created_by' => $createdBy,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    public function notifyRole(
        string $role,
        string $type,
        string $title,
        ?string $message = null,
        ?string $link = null,
        ?array $data = null,
        ?int $createdBy = null,
    ): void {
        $users = User::role($role)->get();
        foreach ($users as $user) {
            $this->notifyUser($user->id, $type, $title, $message, $link, $data, $createdBy);
        }
    }

    public function notifyAdmins(
        string $type,
        string $title,
        ?string $message = null,
        ?string $link = null,
        ?array $data = null,
    ): void {
        $this->notifyRole('Administrador', $type, $title, $message, $link, $data);
        $this->notifyRole('Gerente', $type, $title, $message, $link, $data);
    }

    public function stockAlert(int $ingredientId, string $ingredientName, float $currentStock, float $minStock): void
    {
        $this->notifyAdmins(
            'stock_alert',
            "Stock bajo: $ingredientName",
            "Quedan " . number_format($currentStock, 2) . " de $ingredientName (mínimo: " . number_format($minStock, 2) . ")",
            '/inventory/ingredients',
            ['ingredient_id' => $ingredientId, 'current' => $currentStock, 'minimum' => $minStock],
        );
    }

    public function orderReady(int $orderId, ?int $waiterId, string $tableName): void
    {
        if ($waiterId) {
            $this->notifyUser(
                $waiterId,
                'order_ready',
                "Comanda lista: $tableName",
                "La comanda de $tableName está lista para servir",
                '/pos/tables',
                ['order_id' => $orderId],
            );
        }
    }

    public function cashClosurePending(int $userId, string $registerName): void
    {
        $this->notifyUser(
            $userId,
            'cash_closure',
            'Cierre de caja pendiente',
            "El turno \"$registerName\" aún no se ha cerrado",
            '/cashier',
        );
    }

    public function sriError(int $orderId, string $error): void
    {
        $this->notifyAdmins(
            'sri_error',
            'Error factura SRI',
            "Orden #$orderId: $error",
            '/cashier',
            ['order_id' => $orderId, 'error' => $error],
        );
    }
}
