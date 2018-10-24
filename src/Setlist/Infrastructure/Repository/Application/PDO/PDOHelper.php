<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

trait PDOHelper
{
    public function getLimitString(int $start, int $length): string
    {
        $limitString = '';
        if ($length > 0) {
            $limitString = ' LIMIT ';
            if ($start > 0) {
                $limitString .= $start . ', ';
            }
            $limitString .= $length;
        }

        return $limitString;
    }
}
