<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\ORM\Promise\Collection\CollectionPromise;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\Security\Random;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *     repository="App\Repository\UserRepository",
 *     mapper="Yiisoft\Yii\Cycle\Mapper\TimestampedMapper"
 * )
 * @Cycle\Annotated\Annotation\Table(
 *     indexes={
 *         @Cycle\Annotated\Annotation\Table\Index(columns={"login"}, unique=true),
 *         @Cycle\Annotated\Annotation\Table\Index(columns={"token"}, unique=true)
 *     }
 * )
 */
class User implements IdentityInterface
{
    /**
     * @Cycle\Annotated\Annotation\Column(type="primary")
     */
    private ?int $id = null;

    /**
     * @Cycle\Annotated\Annotation\Column(type="string(128)")
     */
    private string $token;

    /**
     * @Cycle\Annotated\Annotation\Column(type="string(48)")
     */
    private string $login;

    /**
     * @Cycle\Annotated\Annotation\Column(type="string")
     */
    private string $passwordHash;

    /**
     * Annotations for this field placed in a mapper class
     */
    private DateTimeImmutable $created_at;

    /**
     * Annotations for this field placed in a mapper class
     */
    private DateTimeImmutable $updated_at;

    /**
     * @Cycle\Annotated\Annotation\Relation\HasMany(
     *     target="App\Entity\Task",
     *     fkAction="CASCADE"
     * )
     * @var Task[] | CollectionPromise
     */
    private $tasks;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
        $this->setPassword($password);
        $this->resetToken();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id === null ? null : (string)$this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function resetToken(): void
    {
        $this->token = Random::string(128);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function validatePassword(string $password): bool
    {
        return (new PasswordHasher())->validate($password, $this->passwordHash);
    }

    public function setPassword(string $password): void
    {
        $this->passwordHash = (new PasswordHasher())->hash($password);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * @return ArrayCollection|Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    public function addTask(Task $task): void
    {
        $this->tasks->add($task);
    }
}
