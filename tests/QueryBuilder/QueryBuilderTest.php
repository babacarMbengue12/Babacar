<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 30/11/2018
 * Time: 17:27
 */

namespace Tests\QueryBuilder;


use App\Container\Container;
use App\Entity\PostEntity;
use App\QueryBuilder\Hydrator;
use App\QueryBuilder\QueryBuilder;
use App\Table\ImageTable;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
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


    /**
     * @return QueryBuilder
     */
    private function make()
    {
        return new QueryBuilder($this->container->get(\PDO::class), $this->container);

    }//end make()


    public function testSelect()
    {

        $query2 = $this->make()->select('name', "content")->from('post', 'p');
        $this->assertEquals('SELECT name, content FROM post as p', (string) $query2);

    }//end testSelect()


    public function testJoin()
    {
        // $query = $this->make()->select('name')->from('post');
        $query = $this->make()
            ->select('p.name', "p.content", "i.image as image", 'i2.name as image2')
            ->from('post', 'p')
            ->leftJoin("image as i", "p.id = i.id")
            ->rightJoin('image2 as i2', "p.id = i2.id")
            ->innerJoin('image3 as i3', "p.id = i3.id");

        // $this->assertEquals('SELECT name FROM post',(string)$query);
        $this->assertEquals(
            'SELECT p.name, p.content, i.image as image, i2.name as image2 FROM post as p LEFT JOIN image as i ON p.id = i.id RIGHT JOIN image2 as i2 ON p.id = i2.id INNER JOIN image3 as i3 ON p.id = i3.id',
            (string) $query
        );

    }//end testJoin()


    public function testLimit()
    {

        $query2 = $this->make()->select('name')->from('post', 'p')->limit(10);
        $query  = $this->make()->select('name')->from('post', 'p')->limit(10, 100);

        $this->assertEquals('SELECT name FROM post as p LIMIT 10', (string) $query2);

        $this->assertEquals('SELECT name FROM post as p LIMIT 10, 100', (string) $query);

    }//end testLimit()


    public function testOrder()
    {

        $query = $this->make()
            ->select('name')
            ->from('post', 'p')
            ->limit(10, 100)
            ->order("created_at")
            ->order("updated_at")
            ->order("name", "ASC");

        $this->assertEquals('SELECT name FROM post as p ORDER BY created_at DESC, updated_at DESC, name ASC LIMIT 10, 100', (string) $query);

    }//end testOrder()


    public function testWhere()
    {

        $query = $this->make()
            ->select('name')
            ->from('post', 'p')
            ->orWhere("p.content = :content")
            ->andWhere("p.name = :name")
            ->where('id = :id');

        $this->assertEquals('SELECT name FROM post as p WHERE (id = :id) OR (p.content = :content) AND (p.name = :name)', (string) $query);

    }//end testWhere()


    /**
     * @throws \ReflectionException
     */
    public function testWhere2()
    {

        $query = $this->make()
            ->select('name')
            ->from('post', 'p')
            ->andWhere("p.name = :name", "p.test = :test")
            ->where('id = :id');

        $this->assertEquals('SELECT name FROM post as p WHERE (id = :id) AND (p.name = :name AND p.test = :test)', (string) $query);

    }//end testWhere2()


    public function testCount()
    {

        $count  = $this->make()
            ->select('name')
            ->from('post', 'p')
            ->count();
        $count2 = $this->make()
            ->select('name')
            ->from('post', 'p')
            ->fetchAll();

        $this->assertEquals(count($count2), $count);

        $this->assertInstanceOf(\stdClass::class, $count2[0]);

    }//end testCount()


    public function testLazy()
    {
        $posts = $this->make()
            ->from('post', 'p')
            ->select('p.name')
            ->limit(10)
            ->into(PostEntity::class)
            ->fetchAll();

        $post1 = $posts[0];
        $post2 = $posts[0];

        $this->assertSame($post1, $post2);
        $this->assertSame($post1, $post2);
        $this->assertInstanceOf(PostEntity::class, $post1);
        $this->assertInstanceOf(PostEntity::class, $post2);
        $this->assertCount(10, $posts);

    }//end testLazy()


    public function testHydrateObject()
    {
        $post = new PostEntity($this->container->get(ImageTable::class));
        $post->setName('test')
            ->setSlug('azeaze');
        $prams = [
            'id'          => 10,
            'content'     => 'test test',
            'category_id' => 2,
        ];

        /*
         * @var PostEntity $post
         */
        $post = Hydrator::hydrate($prams, $post, $this->container);

        $this->assertEquals(10, $post->getId());
        $this->assertEquals(2, $post->getCategoryId());
        $this->assertEquals('test', $post->getName());
        $this->assertEquals('test test', $post->getContent());
        $this->assertEquals('azeaze', $post->getSlug());

    }//end testHydrateObject()


}//end class
