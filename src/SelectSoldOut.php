<?php
/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:59 PM
 */

namespace My;


class SelectSoldOut
{
    /**
     * Attempt to select sold-out product
     *
     * @return string
     */
    public function __invoke()
    {
        return "SOLD OUT";
    }

}