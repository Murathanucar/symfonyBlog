<?php
/**
 * Created by PhpStorm.
 * User: murathan
 * Date: 20.09.2017
 * Time: 15:23
 */

namespace Tests\AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Controller\ArticleController;

class ArticleControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexAction()
    {
        $articleController =
            $this->getMockBuilder(ArticleController::class)
                 ->disableOriginalConstructor()
                 ->setMethods(["indexAction"])
                 ->getMock();


    }
}
