<?php

namespace App\Infrastructure\User\Repository;

use App\Domain\Repository\UserEventRepositoryInterface;
use App\Domain\User;
use App\Domain\UserId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class UserEventRepository extends AggregateRepository implements UserEventRepositoryInterface
{
    public function store(User $user)
    {
        $this->saveAggregateRoot($user);
    }

    public function get(UserId $userId): ?User
    {
        return $this->getAggregateRoot($userId->toString());
    }
}
