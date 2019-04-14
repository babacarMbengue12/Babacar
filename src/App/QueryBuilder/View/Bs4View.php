<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 01/12/2018
 * Time: 00:17
 */

namespace Babacar\QueryBuilder\View;


class Bs4View
{
    /**
     * @var integer
     */
    private $page;
    /**
     * @var integer
     */
    private $perPage;
    /**
     * @var integer
     */
    private $count;
    /**
     * @var string
     */
    private $url;

    private $nbPage = 0;


    public function __construct(string $url, int $page, int $perPage, int $count)
    {
         $this->page    = $page;
         $this->perPage = $perPage;
         $this->count   = $count;
         $this->url     = $url;

    }//end __construct()


    public function render(array $queryArgs=[])
    {
        if ($this->nbPage === 0) {
            $this->setNbPage();
        }

        $back = $this->page === 1 ? "disabled" : "";
        $next = $this->page === $this->nbPage ? "disabled" : "";

        $uri   = $this->url;
        $html  = [];
        $pages = [];
        if ($this->count < 1) {
            $html[] = '<ul class="pagination m-2">
            <li class="page-item '.$back.'" >
                      <a class="page-link" href="'.$uri.'?p='.($this->page - 1).'" >&lAarr;</a>
             </li>';

            $html[] = '<li class="page-item active" >
                      <a class="page-link" href="'.$uri.'">1</a></li>';

            $html[] = '<li class="page-item '.$next.'">
                          <a class="page-link" href="'.$uri.'?p='.($this->page + 1).'" >&rAarr;</a>
                   </li>
              </ul>';

            return join("", $html);
        }

        for ($i = 1;$i <= $this->nbPage;$i++) {
            $pages[] = $i;
        }

        $html[] = '<ul class="pagination">
             
            <li class="page-item '.$back.'" >
                      <a class="page-link" href="'.$uri.'?p='.($this->page - 1).'" >&lAarr;</a>
             </li>';

        $html = array_merge(
            $html,
            array_map(
                function ($page) use ($uri, $queryArgs) {
                    $active = $this->page === $page ? "active" : "";

                    if (empty($queryArgs)) {
                          return '<li class="page-item '.$active.'" >
                      <a class="page-link" href="'.$uri.'?p='.$page.'">'.$page.'</a></li>';
                    }

                    return '<li class="page-item '.$active.'" >
                      <a class="page-link" href="'.$uri.'&p='.$page.'">'.$page.'</a></li>';
                },
                $pages
            )
        );

        $html[] = '<li class="page-item '.$next.'">
                          <a class="page-link" href="'.$uri.'?p='.($this->page + 1).'" >&rAarr;</a>
                   </li>
              </ul>';

        return join("", $html);

    }//end render()


    private function setNbPage()
    {

        if ($this->perPage !== $this->count) {
            if (($this->count % $this->perPage) !== 0) {
                $this->nbPage = 1;
            }

            $this->nbPage += (int) ($this->count / $this->perPage) ;
        } else {
            $this->nbPage = 1;
        }

    }//end setNbPage()


}//end class
