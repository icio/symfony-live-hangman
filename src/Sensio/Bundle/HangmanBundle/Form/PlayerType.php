<?php

namespace Sensio\Bundle\HangmanBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Add a username, email and rawPassword (repeated password) fields
        // ...
        $builder
        	->add('username')
        	->add('email')
        	->add('rawPassword', 'repeated', array(
        			'type' => 'password',
        			'first_name' => 'password',
        			'second_name' => 'confirmation',));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sensio\Bundle\HangmanBundle\Entity\Player',
        ));
    }

    public function getName()
    {
        return 'player';
    }
}
