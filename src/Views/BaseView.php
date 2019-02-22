<?php

namespace Forum\Views;


class BaseView
{
    protected $twig;
    protected $viewName;

    protected function __construct(string $viewName){
        $this->viewName = $viewName;
        $loader = new \Twig\Loader\FilesystemLoader( 'src/Templates');
        $this->twig = new \Twig\Environment($loader, [
            //'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache',
        ]);
    }

    protected function _render(array $data = []){
        try{
            echo $this->twig->render($this->viewName, ["data"=>$data]);
        } catch(\Exception $e){
            echo "An error occured while rendering view <$this->viewName>\n";
            var_dump($e);
        }
    }
}