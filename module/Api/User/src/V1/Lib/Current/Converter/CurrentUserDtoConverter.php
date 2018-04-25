<?php

namespace Api\User\V1\Lib\Current\Converter;

use Api\User\V1\Lib\Current\Dto\CurrentUserDto;
use User\Entity\User;

/**
 * Class CurrentUserDtoConverter
 *
 * @package Api\User\V1\Lib\Current\Converter
 */
class CurrentUserDtoConverter
{
    /**
     * @param User $user
     *
     * @return CurrentUserDto
     */
    public function convert(User $user)
    {
        $dto = new CurrentUserDto();
        $dto->setId($user->getId())
            ->setUsername($user->getUsername())
            ->setRole($user->getRole());

        return $dto;
    }
}