<?php

namespace App\Domain\Event\UserWasCreated;

use App\Domain\UserId;
use App\Domain\ValueObject\Auth\Credentials;
use App\Domain\ValueObject\Auth\HashedPassword;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use Prooph\EventSourcing\AggregateChanged;

class UserWasCreatedEvent extends AggregateChanged
{
    private $credentials;
    private $name;
    protected $userId;

    public static function withData(UserId $userId, Credentials $credentials, Name $name): UserWasCreatedEvent
    {
        /** @var self $event */
        $event = self::occur($userId->toString(), [
            'name' => $name->toString(),
            'credentials' => [
                'email' => $credentials->email()->toString(),
                'password' => $credentials->hashedPassword()->toString(),
            ],
        ]);

        $event->userId = $userId;
        $event->name = $name;
        $event->credentials = $credentials;

        return $event;
    }

    public function credentials(): Credentials
    {
        if (null === $this->credentials) {
            $this->credentials = new Credentials(Email::fromString($this->payload['credentials']['email']), HashedPassword::fromHash($this->payload['credentials']['password']));
        }

        return $this->credentials;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        if (null === $this->name) {
            list($firstname, $lastname) = explode(' ', $this->payload['name']);
            $this->name = Name::fromString($firstname, $lastname);
        }

        return $this->name;
    }

    /**
     * @return mixed
     */
    public function userId(): UserId
    {
        if (null === $this->userId) {
            $this->userId = UserId::fromString($this->aggregateId());
        }

        return $this->userId;
    }
}