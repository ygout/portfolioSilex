<?php

namespace Portfolio\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjetType extends AbstractType {

    public $categories;

    public function __construct($categories) {
        $this->categories = $categories;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre', 'text')
                ->add('categorie', 'choice', array(
                    'choices' => $this->categories,
                    'expanded' => false,
                    'multiple' => false,
                ))
                ->add('description', 'textarea')
                ->add('bilan', 'textarea')
                ->add('outils', 'textarea')
                ->add('langages', 'textarea');
    }

    public function getName() {
        return 'projet';
    }

}