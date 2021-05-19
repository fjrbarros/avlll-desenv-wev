<?php

namespace App\Utils;

class View
{

    //Variaveis padrões da view
    private static $vars = [];

    //Metodo responsavel por definir os dados iniciais da classe
    public static function init($vars = [])
    {
        self::$vars = $vars;
    }


    //Responsavel por retornar o conteudo da view.
    private static function getContentView($view)
    {
        $file = __DIR__ . '/../../resources/view/' . $view . '.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }

    //Responsavel por retornar o conteudo renderizado da view.
    public static function render($view, $vars = [])
    {
        //Conteudo da view.
        $contentView = self::getContentView($view);

        //Merge de variaveis da view
        $vars = array_merge(self::$vars, $vars);

        //Chaves do array
        $keys = array_keys($vars);

        $keys = array_map(function ($item) {
            return '{{' . $item . '}}';
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);

        //Retorna o conteudo renderizado.
        return $contentView;
    }
}
