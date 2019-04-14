<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27/06/2018
 * Time: 19:34
 */

namespace Babacar\TwigExtension;


use Babacar\Auth\Auth;
use Babacar\Auth\User\Roles;
use Babacar\Container\Container;
use Babacar\Session\Session;

class AuthExtension extends \Twig_Extension
{


    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {

        $this->container = $container;

    }//end __construct()


    public function getFilters()
    {
        return [new \Twig_SimpleFilter('allowed', [$this, 'allowed'])];

    }//end getFilters()


    /**
     * @param Roles[] $roles
     * @param string  $path
     */
    public function allowed(array $roles=[], string $path)
    {

        $path = array_filter(explode("/", $path));

        $path = ($path[1] ?? '/');
        if ($path !== '/') {
            $path = '/'.$path;
        }

        $paths = $this->container->getOnMemory('paths');

        $allowed = $paths[$path] ?? null;
        if ($allowed === null) {
            return false;
        }

        if (empty($roles)) {
            $roles = [(new Roles())->setRole('ANONYMOUS')];
        }

        foreach ($roles as $role) {
            if (in_array($role->getRole(), $allowed)) {
                return true;
            }
        }

        return false;

    }//end allowed()


}//end class
