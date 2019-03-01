<?php namespace App\Services\Crawler;

use App\Models\Crawler\ResultadoBusca;
use Weidner\Goutte\GoutteFacade as Goutte;

class Crawler implements ICrawler
{
    const URL_ALL_VEHICLE  = 'https://www.seminovosbh.com.br/resultadobusca/index/veiculo/carro%susuario/todos/pagina/%s';

    public function CrawlerRepo()
    {

    }

    public function getVehicle($request)
    {

        $url = '';
        if ($request->has('marca')) {
            $url .= 'marca/' . $request->get('marca') . '/';
        }
        if ($request->has('modelo')) {
            $url .= 'modelo/' . $request->get('modelo') . '/';
        }
        if ($request->has('cidade')) {
            $url .= 'cidade/' . $request->get('cidade') . '/';
        }

        if ($request->has('valor1')) {
            $url .= 'valor1/' . $request->get('valor1') . '/';
        }

        if ($request->has('valor2')) {
            $url .= 'valor2/' . $request->get('valor2') . '/';
        }

        if ($request->has('ano1')) {
            $url .= 'ano1/' . $request->get('ano1') . '/';
        }

        if ($request->has('ano2')) {
            $url .= 'ano2/' . $request->get('ano2') . '/';
        }
        $pagina = $request->has('pagina') ? $request->get('pagina') : 1;

        if ($url != '') {
            $url = '/' . $url;
        } else {
            $url = '/';
        }
        $caminho = sprintf(Crawler::URL_ALL_VEHICLE, $url, $pagina);


        $crawler = Goutte::request('GET', $caminho);
        $quantidadePaginasCarros = $this->getPage($crawler);
        $resultados = $this->getSearchResult($crawler);

        $veiculos = [];
        if ($resultados) {
            foreach ($resultados as $key => $value) {
                $veiculos[] = $this->buildResult($value, $key);
            }
        }
        return $veiculos;
    }

    public function getPage($crawler)
    {
        $resultado = $crawler->filter('.total')->each(function ($node) {
            return intval($node->text());
        });
        return !empty($resultado) ? $resultado[0] : 0;
    }

    public function getSearchFilter($crawler)
    {
        $filtroMarcas = $this->getDataFilter($crawler, '#marca');
        $filtroCidades = $this->getDataFilter($crawler, '#idCidade');
        $filtroValorTo = $this->getDataFilter($crawler, '#valor1');
        $filtroValorFrom = $this->getDataFilter($crawler, '#valor2');
        $filtroAnoTo = $this->getDataFilter($crawler, '#ano1');
        $filtroAnoFrom = $this->getDataFilter($crawler, '#ano2');
        return [
            'marcas' => $filtroMarcas,
            'cidades' => $filtroCidades,
            'valoresTo' => $filtroValorTo,
            'valoresFrom' => $filtroValorFrom,
            'anoTo' => $filtroAnoTo,
            'anoFrom' => $filtroAnoFrom,
        ];
    }

    public function getDataFilter($crawler, $nomeFiltro)
    {
        return $crawler->filter($nomeFiltro)->filterXPath('//option[contains(@value, "")]')->each(function ($node) {
            $elemento[$node->extract(['value'])[0]] = $node->text();
            return $elemento;
        });
    }

    public function getSearchResult($crawler)
    {
        $nomes = $crawler->filter('.bg-busca .titulo-busca')->each(function ($node) {
            $desc[] = trim($node->text());
            return $desc;
        });
        $links = $crawler->filter('.bg-busca > dt')->filterXPath('//a[contains(@href, "")]')->each(function ($node) {
            return $elemento[] = $node->extract(['href'])[0];
        });

        $linksDetalhes = [];
        foreach ($links as $key => $value) {
            if (substr($value, 0, 15) != '/veiculo/codigo') {
                $linksDetalhes[] = $value;
            }
        }

        $arrayFinal = [];
        foreach ($nomes as $key => $value) {
            $arrayFinal[$value[0]] = $linksDetalhes[$key];
        }
        return $arrayFinal;
    }

    public function buildResult($detalhesVeiculo, $anuncio)
    {
        $dados = explode('/', $detalhesVeiculo);
        $resultado = new ResultadoBusca();
        $resultado->ano = $dados[4];
        $resultado->id = $dados[5];
        $resultado->marca = $dados[2];
        $resultado->nome = $dados[3];
        $resultado->anuncio = $anuncio;
        return $resultado;
    }
}
