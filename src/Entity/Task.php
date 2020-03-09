<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Yiisoft\Auth\IdentityInterface;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *     repository="App\Repository\TaskRepository",
 *     mapper="Yiisoft\Yii\Cycle\Mapper\TimestampedMapper"
 * )
 */
class Task implements IdentityInterface
{
    /**
     * @Cycle\Annotated\Annotation\Column(type="primary")
     */
    private ?int $id = null;

    /**
     * @Cycle\Annotated\Annotation\Column(type="text")
     */
    private string $task;

    /**
     * @Cycle\Annotated\Annotation\Column(type="date")
     */
    private $date;

    /**
     * @Cycle\Annotated\Annotation\Column(type="integer(1)")
     */
    private int $completed;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(
     *     target="App\Entity\User",
     *     nullable=false,
     *     fkAction="CASCADE"
     * )
     * @var User|\Cycle\ORM\Promise\Reference
     */
    private $user = null;
    private ?int $user_id = null;

    /**
     * Annotations for this field placed in a mapper class
     */
    private DateTimeImmutable $created_at;

    /**
     * Annotations for this field placed in a mapper class
     */
    private DateTimeImmutable $updated_at;

    public function __construct(string $task)
    {
        $this->task = $task;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return 'task';
    }

    public function getPk()
    {
        return $this->id;
    }

    public function setTask($task): void
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return DateTimeImmutable|string
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setCompleted($completed): void
    {
        $this->completed = $completed;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
