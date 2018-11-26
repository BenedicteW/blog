<?php
/**
 * Created by PhpStorm.
 * User: Benedicte
 * Date: 26/11/2018
 * Time: 15:15
 */

namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        return preg_replace(
            '/[^a-z0-9]/', '-', strtolower(trim(strip_tags($input)))
        );
    }
}