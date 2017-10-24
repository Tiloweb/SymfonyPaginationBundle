<?php
namespace Tiloweb\PaginationBundle\Twig\Extension;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationExtension extends \Twig_Extension
{
    private $template;
    private $templateFile;

    public function __construct(\Twig_Environment $template, $templateFile)
    {
        $this->template = $template;
        $this->templateFile = $templateFile;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('pagination', array($this, 'paginationFunction'), array(
                'is_safe' => array('html')
            ))
        );
    }

    public function getName() {
        return 'tiloweb_pagination';
    }

    public function paginationFunction(Paginator $paginator, $get = 'page') {
        return $this->template->render($this->templateFile, array(
            'pages' => ceil($paginator->count() / $paginator->getQuery()->getMaxResults()),
            'get' => $get
        ));
    }
}
