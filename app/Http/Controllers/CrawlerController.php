<?php

namespace App\Http\Controllers;

use App\Services\Crawler\ICrawler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CrawlerController extends Controller
{
    private $crawler;

    public function __construct(ICrawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getCar(Request $request, $id = null)
    {
        $result = $this->crawler->getVehicle($request);
        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $result;
    }
}
