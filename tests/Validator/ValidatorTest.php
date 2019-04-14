<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 09/12/2018
 * Time: 16:18
 */

namespace Tests\Validator;


use App\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{


    private function make(array $prams)
    {
        return new Validator($prams);

    }//end make()


    public function testRequired()
    {
        $errors = $this->make(['name' => 'babacar', 'prenom' => 'mbengue'])
            ->required('name', 'prenom', 'age')
            ->getErrors();

        $this->assertCount(1, $errors);

        $this->assertEquals('le champ age est requis', (string) $errors[0]);

    }//end testRequired()


    public function testNotBlack()
    {
        $errors = $this->make(['name' => 'babacar', 'prenom' => 'mbengue', 'age' => ''])
            ->notBlank('age', 'name', 'prenom', 'azeaze')
            ->getErrors();

        $this->assertCount(1, $errors);

        $this->assertEquals('le champ age ne peut etre vide', (string) $errors[0]);

    }//end testNotBlack()


    public function testLength()
    {
        $errors = $this->make(
            [
                'name'    => 'babacar mbengue',
                'prenom'  => 'mbengue',
                'content' => 'mbengue ndndndndndndndnnndnd',
                'age'     => '1234567',
            ]
        )
            ->length('nom', 5, 25)
            ->length('prenom', 10)
            ->length('content', 10, 20)
            ->length('age', null, 4)
            ->getErrors();

        $this->assertCount(3, $errors);

        $this->assertEquals('le champ prenom doit contenir au moins 10 caracteres', (string) $errors[0]);
        $this->assertEquals('le champ age doit contenir au plus 4 caracteres', (string) $errors[2]);
        $this->assertEquals('le champ content doit contenir entre (10 et 20) caracteres', (string) $errors[1]);

    }//end testLength()


}//end class
