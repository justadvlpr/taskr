<?php

declare(strict_types=1);

namespace App\Repository;

use Cycle\ORM\Select;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Yii\Cycle\DataReader\SelectDataReader;

final class TaskRepository extends Select\Repository
{
    public function getAll(string $userId): SelectDataReader
    {
        $query = $this->select()->where(['user_id' => $userId]);

        return $this->prepareDataReader($query);
    }

    public function getDaily(string $userId): SelectDataReader
    {
        $query = $this->select()
            ->where([
                'date' => date('Y-m-d'),
                'user_id' => $userId
            ]);

        //return $query->fetchAll();
        return $this->prepareDataReader($query);
    }

    private function prepareDataReader($query): SelectDataReader
    {
        return (new SelectDataReader($query))
            ->withSort(
                (new Sort([]))->withOrder(['created_at' => 'desc'])
            );
    }
}
