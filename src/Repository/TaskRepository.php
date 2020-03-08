<?php

namespace App\Repository;

use Cycle\ORM\Select;

final class TaskRepository extends Select\Repository
{
    public function getAll(string $userId): array
    {
        $query = $this->select()->where(['user_id' => $userId]);

        return $query->fetchAll();
    }

    public function getDaily(string $userId): array
    {
        $query = $this->select()
            ->where([
                'date' => date('Y-m-d'),
                'user_id' => $userId
            ]);

        return $query->fetchAll();
    }
}
