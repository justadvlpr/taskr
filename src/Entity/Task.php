<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Yiisoft\Auth\IdentityInterface;

/**
 * @Entity(repository="App\Repository\TaskRepository", mapper="Yiisoft\Yii\Cycle\Mapper\TimestampedMapper")
 */
class Task implements IdentityInterface
{
    /**
     * @Column(type="primary")
     */
    private ?int $id = null;

    /**
     * @Column(type="text")
     */
    private string $task;

    /**
     * @Column(type="date")
     */
    private $date;

    /**
     * @Column(type="integer(1)")
     */
    private int $completed;

    /**
     * @BelongsTo(target="App\Entity\User", nullable=false, fkAction="CASCADE")
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

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setCompleted($completed)
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
