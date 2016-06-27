<?php

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * (description)
 *
 * @author    Piotr Żuralski <piotr@zuralski.net>
 * @copyright 2016 Piotr Żuralski
 * @since     2016-06-27
 */
class SearchForm extends AbstractType
{

    /**
     * Builds form.
     *
     * @param   \Symfony\Component\Form\FormBuilderInterface $builder
     * @param   array $options
     * @return  void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lat', Type\TextType::class, array(
            'label'         => 'Latitude',
            'data'   => '54.348545',
            'required'      => true,
            'constraints'   => array(
                new Constraints\NotBlank(),
                new Constraints\Length(array('min' => 5)),
            ),
        ));

        $builder->add('lng', Type\TextType::class, array(
            'label'         => 'Longitude',
            'data'    => '18.6510408',
            'required'      => true,
            'constraints'   => array(
                new Constraints\NotBlank(),
                new Constraints\Length(array('min' => 5)),
            ),
        ));

        $builder->add('radius', Type\ChoiceType::class, array(
            'label'         => 'Distance radius',
            'required'      => true,
            'choices' => [
                '2 km' => 2000,
                '1 km' => 1000,
                '500 m' => 500,
                '250 m' => 250,
            ],
            'empty_data' => 2000,
            'constraints'   => array(
                new Constraints\NotBlank(),
            ),
        ));

        $builder->add('q', Type\SearchType::class, array(
            'label'         => 'Search',
            'required'      => false,
        ));

        $builder->add('submit', Type\SubmitType::class, array(
            'label' => 'Find',
            'icon' => 'ok',
            'attr' => array(
                'class' => 'btn-primary pull-right',
            ),
        ));
    }



}