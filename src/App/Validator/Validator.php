<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 09/12/2018
 * Time: 16:08
 */

namespace Babacar\Validator;


use Psr\Http\Message\UploadedFileInterface;

class Validator
{
    private $mimType = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
    ];
    /**
     * @var array
     */
    private $prams;
    /**
     * @var ValidationError[] $errors
     */
    private $errors = [];


    public function __construct(array $prams)
    {
        $this->prams = $prams;

    }//end __construct()


    public function required(string ...$keys):self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }

        return $this;

    }//end required()
    public function tel(string $field){
        $value = $this->getValue($field);
        $value = join("",array_map('trim',array_filter(explode(' ',$value))));
        $operator = substr($value,0,2);
        $operators = [70,76,77,78];

        $patern = '/^[0-9]{9}$/';
        if(!preg_match($patern,$value) || !in_array($operator,$operators)){
            $this->addError($field,'tel');
        }

        return $this;
    }
    public function notBlank(string ...$keys):self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (!is_null($value) && empty($value)) {
                $this->addError($key, 'notBlank');
            }
        }

        return $this;

    }//end notBlank()


    public function length(string $key, ?int $min=null, ?int $max=null):self
    {
        $value = $this->getValue($key);
        if (!is_null($value)) {
            $len = mb_strlen($value);
            if (!is_null($min) && !is_null($max) && ($len < $min || $len > $max)) {
                $this->addError($key, 'betweenLength', [$min, $max]);
            } else if (!is_null($min) && $len < $min) {
                $this->addError($key, 'minLength', [$min]);
            } else if (!is_null($max) && $len > $max) {
                $this->addError($key, 'maxLength', [$max]);
            }
        }

        return $this;

    }//end length()


    public function fileType(string $key, array $extensions):self
    {
             /*
             * @var $file UploadedFileInterface
             */
        $file = $this->getValue($key);
        if (!is_null($file) && $file->getError() === UPLOAD_ERR_OK) {

            $mediaType = $file->getClientMediaType();
            $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
            $type      = $this->mimType[$extension] ?? null;
            if (is_null($type) || ($type !== $mediaType) || !in_array($extension, $extensions)) {
                $this->addError($key, 'file', [join(",", $extensions)]);
            }
        }

        return $this;

    }//end fileType()


    public function uploaded(string $key):self
    {
        $file = $this->getValue($key);
        /*
         * @var $file UploadedFileInterface
         */

        if (is_null($file) || $file->getError() !== UPLOAD_ERR_OK) {
            $this->addError($key, 'uploaded');
        }

        return $this;

    }//end uploaded()


    public function is_valid()
    {
        return empty($this->errors);

    }//end is_valid()


    /**
     * @return array
     */
    public function getErrors():array
    {
        return $this->errors;

    }//end getErrors()


    private function addError(string $key, string $rule, array $expected=[])
    {

        $this->errors[$key] = new ValidationError($key, $rule, $expected);



    }//end addError()


    public function getValue(string $key)
    {
        if (isset($this->prams[$key])) {
            return $this->prams[$key];
        }

        return null;

    }//end getValue()


}//end class
