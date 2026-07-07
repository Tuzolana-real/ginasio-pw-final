<?php

/**
 * Classe responsável por consumir a API pública Exchange Rate,
 * para converter preços de Kwanzas (AOA) para outras moedas.
 */
class ExchangeRate
{
    private const API_URL = 'https://open.er-api.com/v6/latest/AOA';

    /**
     * Devolve um array com as taxas de câmbio a partir de AOA,
     * ou null em caso de falha na ligação à API.
     */
    public static function obterTaxas()
    {
        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // não deixar a página pendurada se a API estiver em baixo
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $resposta = curl_exec($ch);
        $erro = curl_error($ch);
        curl_close($ch);

        if ($erro || !$resposta) {
            return null;
        }

        $dados = json_decode($resposta, true);

        if (!isset($dados['rates'])) {
            return null;
        }

        return $dados['rates'];
    }

    /** Converte um valor em Kwanzas para a moeda indicada (ex: 'USD', 'EUR'). */
    public static function converter($valorEmKz, $moedaDestino)
    {
        $taxas = self::obterTaxas();

        if ($taxas === null || !isset($taxas[$moedaDestino])) {
            return null;
        }

        return round($valorEmKz * $taxas[$moedaDestino], 2);
    }
}