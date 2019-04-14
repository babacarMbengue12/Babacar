<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 09/12/2018
 * Time: 16:12
 */

namespace Babacar\Validator;


class ValidationError
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $rule;
    /**
     * @var array
     */
    private $expected = [];
    private $messages = [
        'required'      => 'le champ %s est requis',
        'notBlank'      => 'le champ %s ne peut etre vide',
        'minLength'     => 'le champ %s doit contenir au moins %d caracteres',
        'maxLength'     => 'le champ %s doit contenir au plus %d caracteres',
        'betweenLength' => 'le champ %s doit contenir entre (%d et %d) caracteres',
        'file'          => 'vous devez uploader dans %s  un fichier valide (%s)',
        'uploaded'      => 'vous devez uploader  dans %s un fichier',
        'tel'      => 'vous devez Entrer un numero valide',
    ];


    /**
     * ValidationError constructor.
     *
     * @param string $key
     * @param string $rule
     * @param array  $expected
     */
    public function __construct(string $key, string $rule, array $expected=[])
    {
        $this->key      = $key;
        $this->rule     = $rule;
        $this->expected = $expected;

    }//end __construct()


    public function __toString()
    {
        $prams = array_merge([$this->messages[$this->rule]], array_merge([$this->key], $this->expected));

        return (string) call_user_func_array('sprintf', $prams);

    }//end __toString()


}//end class
