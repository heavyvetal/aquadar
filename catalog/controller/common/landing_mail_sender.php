<?php

class ControllerCommonLandingMailSender extends Controller {
    public function index(){
        $subject = 'Тест почты';

        $message  = '<h3>Тест почты '.$this->request->post['landing'].'</h3>';
        $message  .= '<br>Имя: ';
        $message  .= isset($this->request->post['name']) ? $this->request->post['name'] : 'не указано';
        $message  .= '<br>Телефон: ';
        $message  .= isset($this->request->post['phone']) ? $this->request->post['phone'] : 'не указан';
        $message  .= '<br>Почта: ';
        $message  .= isset($this->request->post['mail']) ? $this->request->post['mail'] : 'не указана';

        $admin_email_to = $this->config->get('config_mail_alert_email')?? $this->config->get('config_email');

        $mail = new Mail($this->config->get('config_mail_engine'));
        $mail->setTo($admin_email_to);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setHTML($message);

        //$mail->send();
        $this->response->setOutput($admin_email_to);
    }

    public function send()
    {
        $subject = 'Заявка с лендинга';

        $message  = '<h3>Заявка с лендинга '.$this->request->post['landing'].'</h3>';
        $message  .= '<br>Имя: ';
        $message  .= isset($this->request->post['name']) ? $this->request->post['name'] : 'не указано';
        $message  .= '<br>Телефон: ';
        $message  .= isset($this->request->post['phone']) ? $this->request->post['phone'] : 'не указан';
        $message  .= '<br>Почта: ';
        $message  .= isset($this->request->post['mail']) ? $this->request->post['mail'] : 'не указана';

        $admin_email_to = $this->config->get('config_mail_alert_email')?? $this->config->get('config_email');

        $mail = new Mail($this->config->get('config_mail_engine'));
        $mail->setTo($admin_email_to);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setHTML($message);
        $mail->send();

        //$this->response->setOutput($message);
    }
}