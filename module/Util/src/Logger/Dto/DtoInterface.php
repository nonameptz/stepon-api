<?php

namespace Util\Logger\Dto;

/**
 * Interface DtoInterface
 *
 * @package Application\Log\Dto
 */
interface DtoInterface
{

    /**
     * Make array from object
     *
     * @param bool $wrapIntoSection
     *
     * @return mixed
     */
    public function toArray($wrapIntoSection = true);
}
