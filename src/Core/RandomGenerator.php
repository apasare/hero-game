<?php

namespace SimpleCoding\Core;

/**
 * @singleton
 */
class RandomGenerator
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public function rand($min, $max)
    {
        if (function_exists('random_int')) {
            // php 7.0+
            return random_int($min, $max);
        } else if (function_exists('openssl_random_pseudo_bytes')) {
            $range = $max-$min;
            $bits = strlen(decbin($range));
            $bytes = ceil($bits/8);
            $bitmask = (1<<$bits) - 1;

            do {
                $result = hexdec(
                    bin2hex(
                        openssl_random_pseudo_bytes($bytes)
                    )
                ) & $bitmask;
            } while ($result > $range);

            return $result + $min;
        }

        mt_srand();
        return mt_rand($min, $max);
    }
}
