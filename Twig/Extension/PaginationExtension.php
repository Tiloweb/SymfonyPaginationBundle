<?php
namespace Tiloweb\PaginationBundle\Twig\Extension;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaginationExtension extends \Twig_Extension
{
    private $template;
    private $templateFile;
    private $request;

    /**
     * PaginationExtension constructor.
     * @param \Twig_Environment $template
     * @param $templateFile
     * @param RequestStack $requestStack
     */
    public function __construct(\Twig_Environment $template, string $templateFile, RequestStack $requestStack)
    {
        $this->template = $template;
        $this->request = $requestStack;
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
        $request = $this->request->getCurrentRequest();
        $pages = ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
        $page = $request->query->getInt($get);

        if($page > $pages) {
            $page = 1;
        }

        return $this->template->render($this->templateFile, array(
            'pages' => $pages,
            'page' => $page,
            'get' => $get
        ));
    }
}
