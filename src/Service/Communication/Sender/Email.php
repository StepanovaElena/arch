<?php

declare(strict_types=1);

namespace Service\Communication\Sender;

use Model;
use Model\Entity\User;
use Service\Communication\CommunicationInterface;

class Email extends User implements CommunicationInterface
{
    /**
     * @inheritdoc
     */
    public function process(
        User $user,
        string $templateName,
        array $params = []
    ): void {
        // Вызываем метод по формированию тела письма и последующей отправки
    }
}
