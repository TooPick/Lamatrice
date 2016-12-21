<?php

namespace AppBundle\Twig;

use AppBundle\Enum\CategoryTypeEnum;
use Symfony\Component\DependencyInjection\Container;

class ProductExtension extends \Twig_Extension {

    protected $doctrine;
    protected $container;

    public function __construct($doctrine, Container $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    public function getName()
    {
        return 'product_extension';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getCategoryLabel', [$this, 'getCategoryLabel'], ['is_safe' => ['html']]),
        ];
    }

    public function getCategoryLabel($name)
    {
        return CategoryTypeEnum::getTypeName($name);
    }
}