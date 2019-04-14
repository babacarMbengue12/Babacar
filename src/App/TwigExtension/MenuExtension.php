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
use Babacar\Modules\Menu\MenuInterface;
use Babacar\Session\Session;

class MenuExtension extends \Twig_Extension
{


    /**
     * @var Container
     */
    private $container;


    public function __construct(Container $container)
    {

        $this->container = $container;

    }//end __construct()


    public function getFunctions()
    {
        return [new \Twig_SimpleFunction('render_menu', [$this, 'renderMenu'])];

    }//end getFunctions()


    public function RenderMenu(string $name):string
    {

        /*
         * @var $menus MenuInterface[]
         */
        $menus = $this->container->get('menu');
        if (isset($menus[$name])) {
            return $menus[$name]->renderMenu();
        }

        return '';

    }//end RenderMenu()


}//end class
