<?php

namespace Controller;
/**
 * this class contains methods from all controllers with the same names, but this controller and these methods are used in the case of requestMethod = post.
 */
class FormController
{
    public function item(array $data)
    {
        echo 'I\'m method item of FormController';
    }

    public function edit(array $data)
    {

    }
}

?>