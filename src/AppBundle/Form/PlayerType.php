<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 28/02/17
 * Time: 19:16
 */

namespace AppBundle\Form;


use\AppBundle\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PlayerType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('numero', IntegerType::class)
            ->add('position', TextType::class)
            ->add('submit', SubmitType::class)

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Player::class
            ]
        );
    }
    public function getName()
    {
        return 'app_bundle_player_type';
    }
}