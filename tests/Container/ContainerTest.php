<?php
namespace Tests\Container;


use App\Container\Container;
use function App\Container\{
    add, delete, factory, get, make, object
};
use App\Exception\NotFoundException;
use App\Table\Table;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;


    public function setUp()
    {
        $this->container = new Container();
        $this->container->addDefinition(dirname(dirname(__DIR__)).'/config.php');

    }//end setUp()


    public function testGet()
    {

        $pdo  = $this->container->get(\PDO::class);
        $pdo2 = $this->container->get(\PDO::class);
        $this->assertSame($pdo, $pdo2);
        $this->assertInstanceOf(\PDO::class, $pdo);

    }//end testGet()


    public function testMake()
    {

        $Table1 = $this->container->get(Table::class);
        $Table2 = $this->container->make(Table::class);
        $pdo    = $this->container->get(\PDO::class);
        $this->assertSame($pdo, $Table2->getPdo());
        $this->assertNotSame($Table2, $Table1);

    }//end testMake()


    public function testConstructor()
    {
        $this->container->addDefinition(
            [
                'twig' => [
                    'name1',
                    'name2',
                    'name3',
                ],
            ]
        );
        $this->container->addDefinition(
            [
                Test::class  => get(Test::class)
                ->constructor(get(Test2::class), get('twig')),
                Test2::class => get(Test2::class)
                    ->constructorParameter('str1', 'test')
                    ->constructorParameter('b', 10),
            ]
        );

        $test  = $this->container->get(Test::class);
        $test2 = $this->container->get(Test2::class);
        $this->assertEquals('test', $test2->getStr1());
        $this->assertEquals(10, $test2->getB());
        $this->assertSame($test2, $test->getTest2());

    }//end testConstructor()


    public function testSingleTon()
    {
        $this->container->addDefinition(
            [
                'twig' => [
                    'name1',
                    'name2',
                    'name3',
                ],
            ]
        );
        $this->container->addDefinition(
            [
                Test::class  => make(Test::class)
                ->constructor(get(Test2::class), get('twig')),
                Test2::class => get(Test2::class)
                    ->constructorParameter('str1', 'test')
                    ->constructorParameter('b', 10),
            ]
        );
        $twig1  = $this->container->get(\Twig_Environment::class);
        $twig2  = $this->container->get(\Twig_Environment::class);
        $test2  = $this->container->get(Test2::class);
        $test_1 = $this->container->get(Test::class);
        $test_2 = $this->container->get(Test::class);
        $test_3 = $this->container->get(Test::class);

        $this->assertSame($test2, $test_1->getTest2());
        $this->assertSame($test2, $test_2->getTest2());
        $this->assertSame($test2, $test_3->getTest2());
        $this->assertNotSame($test_1, $test_2);
        $this->assertNotSame($twig1, $twig2);
        $this->assertNotSame($test_1, $test_3);
        $this->assertNotSame($test_2, $test_3);

    }//end testSingleTon()


    public function testObject()
    {
        $this->container->addDefinition(
            [
                TestInterface::class => object(Test3::class),
            ]
        );

        $test3  = $this->container->get(TestInterface::class);
        $test32 = $this->container->get(TestInterface::class);

        $this->assertInstanceOf(Test3::class, $test3);
        $this->assertSame($test32, $test3);

    }//end testObject()


    public function testAdd()
    {
        $this->container->addDefinition(
            [
                'twig' => [
                    'test1',
                    'test2',
                    'test3',
                ],
            ]
        );
        $this->container->addDefinition(
            [
                'twig' => add(['test4', 'test5']),
            ]
        );
        $twig = $this->container->get('twig');
        $this->container->addDefinition(
            [
                'twig' => delete(['test4']),

            ]
        );

        $twig2 = $this->container->get('twig');

        $this->assertEquals(['test1', 'test2', 'test3', 'test4', 'test5'], $twig);

        $this->assertEquals(['test1', 'test2', 'test3', 'test5'], $twig2);

    }//end testAdd()


    public function testFactory()
    {
        $this->container->addDefinition(
            [
                'factory' => factory(
                    function (Container $container) {
                        return 'salut';
                    }
                ),
            ]
        );

        $salut = $this->container->get('factory');
        $this->assertEquals('salut', $salut);

    }//end testFactory()


    public function testTemporaryGEt()
    {
        $this->container->temporarySet('name', 'babacar');
        $name = $this->container->temporaryGet('name');

        $this->assertEquals('babacar', $name);

        $this->expectException(NotFoundException::class);
        $this->container->get('name');

    }//end testTemporaryGEt()


}//end class
