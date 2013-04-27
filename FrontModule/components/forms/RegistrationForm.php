<?php

namespace FrontModule\Components\Forms;

use Nette\Application\UI\Form;

class ReservationForm extends BaseForm {

   
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);
        
                        
        $this->addText('club', 'Jméno oddílu')
                            ->addRule(Form::MAX_LENGTH, 'Maximální délka pro jméno oddílu je %d znaků', 100)
                            ->setRequired('Zadejte jméno oddílu');
        $this->addText('phone', 'Telefon:')
                            ->addRule(Form::MAX_LENGTH, 'Maximální délka pro telefon je %d znaků', 50)
                            ->setRequired('Zadejte telefon');
        $this->addText('name', 'Zodpovědná osoba:')
                            ->addRule(Form::MAX_LENGTH, 'Maximální délka jména je %d znaků', 100)
                            ->setRequired('Zadejte zodpovědnou osobu');
        $this->addText('email', 'Email:')
                             ->addRule(Form::MAX_LENGTH, 'Maximální délka pro email je %d znaků', 100)
                              ->setRequired('Zadejte email')
                             ->addRule(Form::EMAIL, 'Email není ne správném formátu');
        
        $this->addCheckbox('agree', 'Souhlas se zpracováním osobních údajů pro marketingové účely Unisport Trade s.r.o.')
                             ->setRequired('Musíte souhlasit se zpracováním osobmích údajů');
        
        $this->addHidden('start_time');
        
        $this->addSubmit('send', 'Rezervovat');
        
        
        $this->onSuccess[] = array($this, 'formSubmited'); 
           
    }

    
    public function formSubmited($form) {       

        $formValues = $form->getValues();        
        
        $startTimestamp = $formValues['start_time'];
        
        $params = $this->presenter->context->parameters;
        
        $startTimeText = date('H:i', $formValues['start_time']);
        $endTimeText = date('H:i', $formValues['start_time'] + strtotime('+'.$params['slotLength'],0));
        $dateText = date('j.n.Y', $formValues['start_time']);
        
        $actionStart = date('j.n.Y', strtotime($params['minDate']));
        $actionEnd = date('j.n.Y', strtotime($params['maxDate']));
        
//        dump($startTimeText, $endTimeText);
//        die();
        
        unset($formValues['agree']);
        
        $success = $this->presenter->slotModel->reserveSlot($formValues);
        
        if ($success) {
            
            
            $template = new \Nette\Templating\FileTemplate(APP_DIR . '/templates/emailTemplates/confirm.latte');
            $template->registerFilter(new \Nette\Latte\Engine);
            $template->startTimeText = $startTimeText;
            $template->endTimeText = $endTimeText;
            $template->dateText = $dateText;
            $template->actionStart = $actionStart;
            $template->actionEnd = $actionEnd;
            //$template->basePath = $this->baseUri;
            
            $attachments = array(
                            WWW_DIR . '/attachments/Pozvanka_TEAMS.pdf'
            );
            
            $this->presenter->mailerService->sendMail($formValues['email'], $template, 'Úspěšná registrace na UMBRO TEAMS HAPPY DAYS', NULL, $attachments);
            
            
            $text = "Děkujeme za vyplnění formuláře, byl jste úspěšně zaregistrován na akci UMBRO TEAMS HAPPY DAYS dne $dateText v $startTimeText - $endTimeText hodin. Na Vámi uvedenou e-mailovou adresu byly zaslány informace k akci, včetně pozvánky.";
            $this->presenter->flashMessage($text);
        } else {
            $this->presenter->flashMessage('Termín '.$startTimeText.' - '.$endTimeText.' dne '.$dateText.'není možné rezervovat. Tento termín je již obsazen', 'error');
        }
        $this->presenter->redirect('this');
        
    }
    
    

}

