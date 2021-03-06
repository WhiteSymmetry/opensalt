<?php

namespace CftfBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class LsDocType extends AbstractLsDocCreateType
{
    /**
     * @param FormBuilderInterface $builder
     */
    protected function addOwnership(FormBuilderInterface $builder)
    {
        $builder
            // TODO: These are placeholder, they should be determined upon creation with a choice of Org or User ownership
            ->add('org', EntityType::class, [
                'required' => false,
                'disabled' => true,
                'placeholder' => 'None',
                'label' => 'Owning Organization',
                'class' => 'Salt\UserBundle\Entity\Organization',
                'choice_label' => 'name',
            ])
            ->add('user', EntityType::class, [
                'required' => false,
                'disabled' => true,
                'placeholder' => 'None',
                'label' => 'Owning User',
                'class' => 'Salt\UserBundle\Entity\User',
                'choice_label' => 'username',
            ])
        ;
    }
}
