<?php


namespace App\Form;


use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('remember_me', CheckboxType::class, [
                'required'=>false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver -> setDefaults([
            'data_class' => Account::class
        ]);
    }


}
