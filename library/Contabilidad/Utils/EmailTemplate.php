<?php
Class Contabilidad_Utils_EmailTemplate
{

    protected static $_instance = null;

    //parts to remove of the templates
    public $tempOptions = array("no_footer", "no_top", "no_left", "no_right", "no_logo");

    //default Template
    private $_defTemplate = "email-template-standard.phtml";

    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return (self::$_instance);
    }

    /**
     * create a string with an specific template and params
     *
     * @param String $template
     * @param Array $params
     * @return String
     */
    public static function createEmailTemplate($template, $params = array()) {
        $html =  Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
        $root = ROOT;
        $html->addBasePath($root . '/application/views/scripts/email-templates/');
        $html->assign($params);
        return $html->render($template);
    }
    
    public function sendWelcomeEmail($user){
        $options = array("template" => "welcome.phtml");
        $options["template_variables"] = array("user" => $user,
                                               "mailType" => "welcome");
        $this->sendEmails($user->email, $options);
    }
    
    public function sendFeedback($extra){
        $email = "support@quantups.com";
        $options = array("template" => "feedback.phtml");
        $options["template_variables"] = array("extra" => $extra,
                                               "mailType" => "feedback");
        $this->sendEmails($email, $options);
    }
    
    public function sendRecoverPassword($user){
        $options = array("template" => "recoverPassword.phtml");
        $options["template_variables"] = array("user" => $user,
                                               "mailType" => "recoverPassword");
        $this->sendEmails($user->email, $options);
    }
    
    public function sendWelcomeEmailAndPassword($user, $password){
        $options = array("template" => "welcomeSocialNetwokUser.phtml");
        $options["template_variables"] = array("user" => $user,
                                               "addPassword" => true,
                                               "password" => $password,
                                               "mailType" => "welcome");
        $this->sendEmails($user->email, $options);
    }

    /**
     * SendMail to specific emails
     *
     * @param String|Array $emails
     * @param Array $params
     */
    public function sendEmails($emails, $options = array()) {

        $view =  Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
        
        //if template and plainText are not defined so it doesn't send anything
        if(!isset($options["template"]) && !isset($options["plainText"])) return;

        //check if send one email
        if(is_string($emails)) $emails = array($emails);

        $config = Zend_Registry::get('Config');
        $configFiles = $config->mail;
        $configFiles = $configFiles->toArray();
        $tempVars = $options["template_variables"];

        foreach($emails as $email) {
            
            if(!isset($tempVars['mailType'])){
                $tempVars['mailType'] = "@todo";
            }
            $subject = $this->createEmailSubject($tempVars);
            
            $mail = new Zend_Mail('utf-8');
            $mail->setFrom (
                isset($options["email_from"]) ? $options["email_from"] : 'no-reply@quantups.com',
                isset($options["name_from"]) ? $options["name_from"] : 'Quantups');
            $mail->addTo ($email, $email);
            $mail->setSubject ($subject);

            //check if the template and/or plainText are
            $options['template_variables']['curemail'] = $email;

            $mail->setBodyText(self::createEmailTemplate ($options["template"], array_merge($options["template_variables"], array('mailVersion' => 'text'))));

            $html = self::createEmailTemplate($options["template"], array_merge($options["template_variables"], array('mailVersion' => 'html')));
            $mail->setBodyHtml($html);
            try {
                $mail->send();
            } catch (Exception $e) {
                var_dump("Error al enviar el email");exit();
            }
        }
    }
    
    
    /**
     * translate the subject for the email
     *
     * @param Array $tempVars
     * @return string
     */
    private function createEmailSubject($tempVars)
    {
        $view =  Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
         
        switch ($tempVars['mailType']){
        
            case 'welcome' :
                $transSubject = ucfirst($tempVars['user']->full_name).' '.$view->tr('bienvenido a Quantups');
                break;
            case 'recoverPassword' :
                $transSubject = ucfirst($tempVars['user']->full_name).' '.$view->tr('recupera tu contraseña');
                break;
            case 'feedback' :
                $transSubject = ucfirst('Feedback');
                break;
            default:
                $view->tr('default');
                break;
        }
       
        return $transSubject;
    }
}
