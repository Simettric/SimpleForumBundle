<?php
/**
 * Created by Asier Marqués <asiermarques@gmail.com>
 * Date: 3/5/16
 * Time: 19:34
 */


namespace Simettric\SimpleForumBundle\Interfaces;


interface UserInterface {


    function getId();

    function getUsername();

    function getEmail();

    function getDisplayName();

} 