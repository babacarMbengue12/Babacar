<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27/06/2018
 * Time: 19:34
 */

namespace Babacar\TwigExtension;


class FormExtension extends \Twig_Extension
{


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('field', [$this, 'field'], ['is_safe' => ['html'], 'needs_context' => true])
        ];
    }

    public function field(array $contex, string $name, $value = null, string $label = null, array $option = [])
    {

        $required = $option['required'] ?? true;
        $errors = $contex['errors'][$name] ?? false;

        $type = $option['type'] ?? 'text';
        $attr = $option['attr'] ?? [];
        $attr['required'] = $required;
        $value = $this->getValue($value);
        $dateClass = $option['class'] ?? '';
        $globals = [
            'class' => "form-control form-control-lg {$dateClass} ",
            'name' => $name,
            'id' => $name,
            'type' => $type,
            'attr' => $attr
        ];
        if (isset($option['placeholder'])) {
            $globals['placeholder'] = $option['placeholder'];
        }
        if ($errors) {
            $error = '<small class="form-text text-muted" >' . $errors . '</small>';
            $globals['class'] .= "form-control-danger";
            $labelhtml = '<div class="form-group has-danger">';
        } else {
            $error = '';
            $labelhtml = '<div class="form-group">';
        }


        if ($type === 'select') {

            $field = $this->select($globals, $value, $option['option']);

        } else if ($type === "file") {
            $field = $this->file($globals);
        } elseif ($type === 'textarea') {

            $field = $this->textarea($globals, $value);
        } else {
            $field = '<input ' . $this->getHtmlFromArray($globals) . ' autocomplete="off" value="' . $value . '" />';
        }

        $labelhtml .= '<label class="form-control-label" for="' . $name . '">' . $label . '</label>';

        return $labelhtml . $field . $error . '</div>';
    }


    private function select(array $globals, $basevalue = null, $options)
    {

        if (in_array('type', array_keys($globals))) {
            unset($globals['type']);
        }
        $html = "<select {$this->getHtmlFromArray($globals)} >";

        foreach ($options as $key => $value) {
            if(is_array($basevalue))
            {
                $selected = in_array($key,$basevalue);
            }
            else{
                $selected = $key == $basevalue;
            }
            $keyValues = [
                "value" => "$key",
                "selected" => $selected
            ];
            $html .= "<option {$this->getHtmlFromArray($keyValues)} >{$value}</option>";
        }
        $html .= '</select>';

        return $html;
    }

    private function textarea($globals, $value = null)
    {
        return '<textarea ' . $this->getHtmlFromArray($globals) . ' rows="10" >' . $value . '</textarea>';
    }

    public function getHtmlFromArray(array $globals)
    {
        $html = [];
        foreach ($globals as $key => $value) {
            if($key === 'attr')
            {
                foreach ($value as $k => $v)
                {
                    if($v === true)
                    {
                        $html[]="$k = '$k'";
                    }
                }
            }
            else{
                if ($value === true) {
                    $html[] = $key;
                } else if ($value !== false) {
                    $html[] = "$key=\"$value\"";
                }
            }


        }

        return join(" ", $html);
    }

    private function getValue($value)
    {
        if (is_string($value))
            return $value;
        elseif ($value instanceof \DateTime)
            return $value->format('Y-m-d');

        return $value;
    }

    private function file($globals)
    {
        return "<input {$this->getHtmlFromArray($globals)} />";
    }
}