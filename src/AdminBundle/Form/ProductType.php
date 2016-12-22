<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Enum\CategoryTypeEnum;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ( isset($options["attr"]["stock"])){
            if ( $options["attr"]["stock"] == true ){
                $builder->add('quantity',IntegerType::class,array('label'=>'Quantité')); 
            } else {
               $builder->add('reference')->add('name',TextType::class,array('label'=>'Nom'))->add('description')
                    ->add('category',ChoiceType::class, array(
                'required' => true,
                'choices' => CategoryTypeEnum::getAvailableTypes(),
                'choices_as_values' => true,
                'choice_label' => function($choice) {
                    return CategoryTypeEnum::getTypeName($choice);
                },
                'label'=>'Categorie',
                ))->add('quantityAlert',IntegerType::class,array('label'=>'Alerte Quantité'))->add('price',TextType::class,array('label'=>'Prix'))->add('expirationDate',DateTimeType::class,array('label'=>'Date d\'expiration'))->add('picture', FileType::class,array('label'=>'Photo'))->add('visible')        ;
            }
        } else {
            $builder->add('reference')->add('name',TextType::class,array('label'=>'Nom'))->add('description')
                    ->add('category',ChoiceType::class, array(
                'required' => true,
                'choices' => CategoryTypeEnum::getAvailableTypes(),
                'choices_as_values' => true,
                'choice_label' => function($choice) {
                    return CategoryTypeEnum::getTypeName($choice);
                },
                'label'=>'Categorie',
                ))->add('quantity',IntegerType::class,array('label'=>'Quantité'))->add('quantityAlert',IntegerType::class,array('label'=>'Alerte Quantité'))->add('price',TextType::class,array('label'=>'Prix'))->add('expirationDate',DateTimeType::class,array('label'=>'Date d\'expiration'))->add('picture',FileType::class,array('label'=>'Photo'))->add('visible')        ;
            }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
