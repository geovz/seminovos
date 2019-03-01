<?php namespace App\Services\Vehicle;

use App\Models\Automovel\Automovel;
use Weidner\Goutte\GoutteFacade as Goutte;

class VehicleDetails implements IVehicleDetails
{
    const URL_VEHICLE = 'https://www.seminovosbh.com.br/comprar////%s';

    public function __contruct()
    {

    }
    /**
     * Obtém os resultados da pesquisa de acordo com os filtros passados
     */
    public function getVehicle($request, $id)
    {
        if (!$id) {
            return [];
        }

        $url = sprintf(VehicleDetails::URL_VEHICLE, $id);

        $crawler = Goutte::request('GET', $url);
        $result = $this->getResultSearch($crawler, $id);

        return ($result);
    }

    /**
     * Método responsável por obter os resultados da busca
     */
    public function getResultSearch($crawler, $id)
    {
        $imagem = $crawler->filter('#conteudo')->filterXPath('//img[contains(@src, "")]')->each(function ($node) {
            return $node->extract(['src'])[0];
        });

        foreach ($imagem as $key => $value) {
            if (strpos($value, 'veiculoNaoExiste.png') !== false) {
                return [];
            }
        }

        $imagensVeiculo = $this->getImg($crawler);

        $nomeAnuncio = $crawler->filter('#textoBoxVeiculo > h5')->each(function ($node) {
            return trim($node->text());
        });

        $valorVeiculo = $crawler->filter('#textoBoxVeiculo > p')->each(function ($node) {
            return trim($node->text());
        });

        $detalhes = $crawler->filter('#infDetalhes > span > ul > li')->each(function ($node) {
            return trim($node->text());
        });

        $acessorios = $crawler->filter('#infDetalhes2 > ul > li')->each(function ($node) {
            return trim($node->text());
        });

        $observacoes = $crawler->filter('#infDetalhes3 > ul > p')->each(function ($node) {
            return trim($node->text());
        });

        $contato = $crawler->filter('#infDetalhes4 .texto> ul > li')->each(function ($node) {
            return trim($node->text());
        });

        $dataVehicle = [
            "id"=>$id,
            "detalhes"=>$detalhes,
            "acessorios"=>$acessorios,
            "observacoes"=>$observacoes,
            "contato"=>$contato,
            "nomeAnuncio"=>$nomeAnuncio,
            "valorVeiculo"=>$valorVeiculo, 
            "imagensVeiculo"=>$imagensVeiculo
        ];

        return [$this->buildModel($dataVehicle)];
    }

    /**
     * Método responsável por obter as imagens do resultados da busca
     */
    public function getImg($crawler)
    {
        $imagemPrincipal = $crawler->filter('#fotoVeiculo')->filterXPath('//img[contains(@src, "")]')->each(function ($node) {
            return $node->extract(['src'])[0];
        });

        $imagens = $crawler->filter('#conteudoVeiculo')->filterXPath('//img[contains(@src, "")]')->each(function ($node) {
            return $node->extract(['src'])[0];
        });

        $imagensVeiculo = [];
        foreach ($imagens as $key => $value) {
            if (strpos($value, 'photoNone.jpg') === false) {
                $imagensVeiculo[] = $value;
            }
        }
        return array_merge($imagemPrincipal, $imagensVeiculo);
    }

    /**
     * Método responsável por montar o model com os resultados da busca
     */
    public function buildModel($dataVehicle)
    {
        $result = new Automovel();
        $result->codigo = $dataVehicle['id'];
        $result->nomeAnuncio = empty($dataVehicle['nomeAnuncio']) ? '' : $dataVehicle['nomeAnuncio'][0];
        $result->valorVeiculo = empty($dataVehicle['valorVeiculo']) ? '' : $dataVehicle['valorVeiculo'][0];
        $result->detalhes = $dataVehicle['detalhes'];
        $result->acessorios = $dataVehicle['acessorios'];
        $result->obsevacoes = empty($dataVehicle['observacoes']) ? '' : $dataVehicle['observacoes'][0];
        $result->imagens = $dataVehicle['imagensVeiculo'];

        foreach ($dataVehicle['contato'] as $key => $value) {
            if (strpos($value, 'Visualizações:') !== false) {
                $result->visualizacoes = intval((explode('Visualizações: ', $value))[1]) ? intval((explode('Visualizações: ', $value))[1]) : null;
            }
            if (strpos($value, 'Cadastro em: ') !== false) {
                $result->dataCadastro = explode('Cadastro em: ', $value)[1] ? explode('Cadastro em: ', $value)[1] : null;
            }
        }

        $result->proprietario->nome = empty($dataVehicle['contato']) ? '' : $dataVehicle['contato'][0];
        $result->proprietario->cidade = empty($dataVehicle['contato']) ? '' : $dataVehicle['contato'][1];
        $result->proprietario->contato = empty($dataVehicle['contato']) ? '' :$dataVehicle['contato'][2];

        return $result;
    }
}
