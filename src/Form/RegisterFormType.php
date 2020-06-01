<?php


namespace App\Form;


use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', TextType::class , [ ])
            ->add('email',TextType::class, [ ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => [ ]],
                'required' => true,
                'first_options'  => ['label' => 'Password', 'attr' => ['placeholder' => 'Password']],
                'second_options' => ['label' => 'Confirm Password', 'attr' => ['placeholder' => "Confirm Password"]],
                'mapped' => false,
                'constraints'=> [
                    new NotBlank([
                        'message' => 'Choose a password'
                    ]),
                    new Length([
                        'min'=>8,
                        'minMessage'=>"Password has to have at least 8 characters",
                        'max'=>32,
                        "maxMessage"=>"Password can have max 32 characters"
                    ])
                ]
                ])
            ->add('terms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You have yo agree to our terms!' //TODO: translate this message
                    ])
                ]
            ])
            ->add('newsletter', CheckboxType::class, ['required'=>false]);
        if($_SERVER['IS_NAME_REQUIRE']==true){
            $builder
                ->add('firstName', TextType::class)
                ->add('lastName',TextType::class);
        }else{
            $builder
                ->add('firstName', TextType::class , ['required'=>false])
                ->add('lastName',TextType::class, ['required'=>false]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver -> setDefaults([
            'data_class' => Account::class
        ]);
    }


}