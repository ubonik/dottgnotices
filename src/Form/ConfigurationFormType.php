<?php

declare(strict_types=1);

namespace PrestaShop\Module\Dottgnotices\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigurationFormType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client_id', TextType::class, [
                'label' => $this->trans('Идентификатор приложения (Client Id)', 'Modules.DemoSymfonyFormSimple.Admin'),                
            ])
            ->add('client_secret', TextType::class, [
                'label' => $this->trans('Пароль приложения (Client Secret)', 'Modules.DemoSymfonyFormSimple.Admin'),                
            ])
            ->add('redirect_url', TextType::class, [
                'label' => $this->trans('Redirect Url', 'Modules.DemoSymfonyFormSimple.Admin'),               
            ])
            ->add('webhook_url', TextType::class, [
                'label' => $this->trans('Url для уведомлений банка (webhook Url)', 'Modules.DemoSymfonyFormSimple.Admin'),               
            ])
            ->add('payment_account', TextType::class, [
                'label' => $this->trans('Расчетный счет', 'Modules.DemoSymfonyFormSimple.Admin'),               
            ])
            ->add('telegram_bot_token', TextType::class, [
                'label' => $this->trans('Токен телеграм бота', 'Modules.DemoSymfonyFormSimple.Admin'),               
            ])
            ->add('telegram_channel_id', TextType::class, [
                'label' => $this->trans('Идентификатор телеграм канала', 'Modules.DemoSymfonyFormSimple.Admin'),               
            ])  
        ;
    }
}
