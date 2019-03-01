# Crawler Seminovos
Para iniciar o projeto basta executar os seguintes comandos no diretorio.
composer update;
composer install;
php -S localhost:8000 -t public;

## Buscar todos automoveis da pagina.
{/busca?parametro=valor}
Ex.: /busca?marca=Chevrolet&modelo=1587
Os filtros disponiveis são:
marca(string) : 	Marca do veículo
cidade(int):  Código da cidade
valor1(int): 	12000	Valor mínimo do carro sem casas decimais, somente inteiro
valor2(int):		25000	Valor máximo do carro sem casas decimais, somente inteiro
ano1(int):		2003	Ano máximo do carro com quatro dígitos
ano2(int):		2005	Ano mínimo do carro com quatro dígitos
pagina(int):		3	Páginação

## Detalhes do veiculo

{/automovel/{id}}
Ex.: /automovel/2474566

id(int):	2474566	Id do ve�culo
## Questoes
As outras questoes do teste Est�o dispon�veis em:
public/questions/{numero da quest�o}question.php

