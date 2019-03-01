<?php namespace App\Services\Crawler;

interface ICrawler
{
    public function getVehicle($dados);
    public function getPage($crawler);
    public function getSearchFilter($crawler);
    public function getDataFilter($crawler, $nomeFiltro);
    public function getSearchResult($crawler);
    public function buildResult($detalhesVeiculo, $anuncio);
}
