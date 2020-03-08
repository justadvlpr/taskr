<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Cycle\ORM\Command\Database\Delete;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Factory\Exceptions\NotFoundException;

class TaskController extends Controller
{
    protected function getId(): string
    {
        return 'task';
    }

    public function index(Request $request, ORMInterface $orm): Response
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $orm->getRepository(Task::class);

        /** @var User $user */
        $user = $request->getAttribute('auth_user');

        if (
            isset($request->getQueryParams()['filter']) &&
            $request->getQueryParams()['filter'] === 'today'
        ) {
            $userTasks = $taskRepository->getDaily($user->getId());
        } else {
            $userTasks = $taskRepository->getAll($user->getId());
        }

        $tasks = [];

        foreach ($userTasks as $task) {
            /** @var Task $task */
            $tasks[] = [
                'id' => $task->getPk(),
                'task' => $task->getTask(),
                'date' => $task->getDate()->format('Y-m-d'),
                'completed' => $task->getCompleted(),
            ];
        }

        return $this->renderJson(
            [
                'success' => true,
                'tasks' => $tasks
            ],
            200
        );
    }

    public function view(Request $request, ORMInterface $orm): Response
    {
        $item = $this->findTask($request, $orm);

        return $this->renderJson(
            [
                'date' => $item->getDate()->format('Y-m-d'),
                'task' => $item->getTask(),
            ],
            200
        );
    }

    public function create(Request $request, ORMInterface $orm): Response
    {
        $body = $request->getParsedBody();
        $error = null;

        try {
            $user = $request->getAttribute('auth_user');

            foreach (['task', 'date'] as $name) {
                if (empty($body[$name])) {
                    throw new \InvalidArgumentException("{$name} is required.");
                }
            }

            $task = new Task($body['task']);
            $task->setDate($body['date']);
            $task->setCompleted(0);
            $task->setUser($user);

            $transaction = new Transaction($orm);
            $transaction->persist($task);
            $transaction->run();
        } catch (\Throwable $e) {
            return $this->renderJson(
                [
                    'success' => false,
                    'error' => $e->getMessage(),
                ],
                422
            );
        }

        return $this->renderJson(['success' => true], 200);
    }

    public function update(Request $request, ORMInterface $orm): Response
    {
        // due to a bug on the framework level still
        // when sending a PUT or PATCH $request->getParsedBody() is not getting any data.
        $body = json_decode(file_get_contents("php://input"), true);
        $error = null;

        try {
            $task = $this->findTask($request, $orm);

            foreach (['task', 'date'] as $name) {
                if (empty($body[$name])) {
                    throw new \InvalidArgumentException("{$name} is required.");
                }
            }

            $task->setDate($body['date']);
            $task->setTask($body['task']);

            $transaction = new Transaction($orm);
            $transaction->persist($task);
            $transaction->run();

            return $this->renderJson(['success' => true], 200);
        } catch (\Throwable $e) {
            return $this->renderJson(
                [
                    'success' => false,
                    'error' => $e->getMessage()
                ],
                422
            );
        }
    }

    public function delete(Request $request, ORMInterface $orm): Response
    {
        try {
            $task = $this->findTask($request, $orm);

            $orm->queueDelete($task)->execute();

            return $this->renderJson([], 204);
        } catch(\Throwable $e) {
            return $this->renderJson(
                [
                    'success' => false,
                    'error' => $e->getMessage()
                ],
                422
            );
        }
    }

    private function findTask(Request $request, ORMInterface $orm): Task
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $orm->getRepository(Task::class);
        $id = $request->getAttribute('id', null);

        /** @var Task $item */
        $item = $taskRepository->select()->where(['id' => $id])->fetchOne();

        if ($item === null) {
            throw new NotFoundException('Task not found');
        }

        // only the user can see/modify his tasks
        if ((int)$item->getUserId() !== (int)$request->getAttribute('auth_user')->getId()) {
            throw new NotFoundException('Task not found');
        }

        return $item;
    }
}
