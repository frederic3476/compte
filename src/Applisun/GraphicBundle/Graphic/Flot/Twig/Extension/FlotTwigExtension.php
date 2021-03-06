<?php

namespace Applisun\GraphicBundle\Graphic\Flot\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class FlotTwigExtension extends \Twig_Extension {
    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'flot_graph' => new \Twig_Function_Method($this, 'flotGraph',
                    array(
                        'is_safe' => array('html', 'js'),
                        'needs_environment' => true
                    )
                )
         );
    }

    /**
     * @param string $string
     * @return int
     */
    public function flotGraph (\Twig_Environment $twig, \Applisun\GraphicBundle\Graphic\Flot\Type\AbstractType $graph) {
        return $twig->render('ApplisunGraphicBundle:Graphic\Flot:'.strtolower((new \ReflectionClass($graph))->getShortName()).'.html.twig', $graph->getView());
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'graphic_flot';
    }
}
