<?php
namespace Doctrine\Custom\Annotation;
use Doctrine\Common\Annotations\Annotation;

/**
 * UpdateTimezone annotation
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 */
final class UpdateTimezone extends Annotation
{
    /** @var string */
    public $field;
}
