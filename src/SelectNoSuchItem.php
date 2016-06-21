<?php
/**
 * Created by PhpStorm.
 * User: sheiss
 * Date: 6/20/16
 * Time: 6:56 PM
 */

namespace My;


class SelectNoSuchItem
{
    /**
     * Attempt to select non-existant product
     *
     * @return string
     */
    public function __invoke()
    {
        return "NO SUCH ITEM";
    }
}