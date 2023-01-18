<?php
namespace Tiloweb\PaginationBundle\Twig\Extension;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
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
    public function __construct(Environment $template, string $templateFile, RequestStack $requestStack)
    {
        $this->template = $template;
        $this->request = $requestStack;
        $this->templateFile = $templateFile;
    }

    public function getFunctions(): array {
        return array(
            new TwigFunction('pagination', array($this, 'paginationFunction'), array(
                'is_safe' => array('html')
            ))
        );
    }

    public function paginationFunction(Paginator $paginator, $get = 'page'): string {
        $request = $this->request->getCurrentRequest();
        $pages = ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
        $page = $request->query->getInt($get, 1);

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
