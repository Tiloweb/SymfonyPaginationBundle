Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require tiloweb/pagination-bundle "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Tiloweb\PaginationBundle\TilowebPaginationBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Configure your repository
---------------------------------

```php
<?php
// Bundle/Entity/Repository/User.php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class User extends EntityRepository
{
    public function findByPage($page = 1, $max = 10)
    {
        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$page must be an integer ('.gettype($page).' : '.$page.')'
            );
        }

        if(!is_numeric($page)) {
            throw new \InvalidArgumentException(
                '$max must be an integer ('.gettype($max).' : '.$max.')'
            );
        }
        
        $dql = $this->createQueryBuilder('user');
        $dql->orderBy('user.lastname', 'DESC');

        $firstResult = ($page - 1) * $max;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($max);

        $paginator = new Paginator($query);

        if(($paginator->count() <=  $firstResult) && $page != 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }
}
```

Step 4: Make the request in the controller
---------------------------------

```php
<?php
// Bundle/Controller/DefaultController.php
namespace Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/user", name="app_list_user")
     */
    public function listUserAction(Request $request)
    {
        $db = $this->getDoctrine()->getManager();

        $listUser = $db->getRepository('AppBundle:User')->findByPage(
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('listUser.html.twig', array(
            'listUser' => $listUser
        ));
    }
}
```
