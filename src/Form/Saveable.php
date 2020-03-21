<?php

namespace App\Form;

interface Saveable
{

    /**
     * Function to save the data
     * @param mixed $params
     * @return mixed
     */
    public function save($params);
}