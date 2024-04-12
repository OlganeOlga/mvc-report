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

    public function getCustomAnnotation(string $action): ?string
    {
        $class = new ReflectionClass(MyJsonController::class);
        $method = $class->getMethod($action);

        $annotations = $method->getAttributes(CustomAnnotation::class);

        if (!empty($annotations)) {
            return $annotations[0]->newInstance()->value;
        }

        return null;
    }

}
