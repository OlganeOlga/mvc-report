<?php
namespace App\Api;

/**
 * @Annotation
 * @Target("METHOD")
 */
class CustomAnnotation
{
    public $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
}
