<?php
/**
 * Created by PhpStorm.
 * User: murathan
 * Date: 13.09.2017
 * Time: 21:56
 */

namespace AppBundle\Controller;


use Couchbase\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;



class CrawlerController
{
    /**
     * @Route("/aaa")
     */

    public function crawlerDeneAction()
    {
        $html = file_get_contents('https://lifelock.com/reviews/');
        $crawler = new Crawler($html);

        $crawler = $crawler->filter('#tab-all > ul > li')->each(function (Crawler $node) {
            return $node;
        });

        $title = [];
        $review = [];
        foreach ($crawler as $node ) {
            $title[] = $node->filter('div.generalBoxRight h2')->text();
            $review[] = $node->filter('div.generalBoxRight p.description')->text();
        }

        print_r($title);
        print_r($review);

        return new Response();

    }
}




