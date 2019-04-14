<?php
namespace Tests\Container;
class Test
{
    /**
     * @var Test2
     */
    private $test2;
    private $twig;


    /**
     * Test constructor.
     *
     * @param Test2 $test2
     * @param $twig
     */
    public function __construct(Test2 $test2, $twig)
    {
        $this->test2 = $test2;
        $this->twig  = $twig;

    }//end __construct()


    /**
     * @return Test2
     */
    public function getTest2(): Test2
    {
        return $this->test2;

    }//end getTest2()


    /**
     * @return mixed
     */
    public function getTwig()
    {
        return $this->twig;

    }//end getTwig()


}//end class


class Test2
{
    /**
     * @var string
     */
    private $str1;
    /**
     * @var integer
     */
    private $b;


    public function __construct(string $str1, int $b)
    {

        $this->str1 = $str1;
        $this->b    = $b;

    }//end __construct()


    /**
     * @return string
     */
    public function getStr1(): string
    {
        return $this->str1;

    }//end getStr1()


    /**
     * @return int
     */
    public function getB(): int
    {
        return $this->b;

    }//end getB()


}//end class

interface TestInterface
{


    public function get(string $name):string ;


}//end interface

class Test3 implements TestInterface
{


    public function get(string $name): string
    {
        echo "$name";

        return $name;

    }//end get()


}//end class
