<?php

namespace App\Repository\Notification;

use App\Notifications\InvoicePaid;

class NotificationRepository {

    function notificationTransferEmail($user) {
        $user->notify(new InvoicePaid($user));
    }
}