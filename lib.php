<?php

abstract class AMVObject
{

    /***
     * set object properties
     * @param:  assoc
     * @return: void
     */
    public function setProperties()
    {
        $args = func_get_args();

        if (count($args) > 0) {

            foreach ($args as $key=>$val) {

                $this->$key = $val;

            }

        }

    }

}

abstract class AMVDatabase
{

}

abstract class AMVModel
{

}

abstract class AMVView
{

}

abstract class AMVController
{

}