<?php
/**
 * My new Zend Framework project
 * 
 * @author  
 * @version 
 */
require_once 'Zend/Controller/Plugin/Abstract.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Request/Abstract.php';
require_once 'Zend/Controller/Action/HelperBroker.php';
require_once "Zend/Loader.php";

/**
 * Initializes configuration depndeing on the type of environment 
 * (test, development, production, etc.)
 *  
 * This can be used to configure environment variables, databases, 
 * layouts, routers, helpers and more
 *   
 */
class Initializer extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Config
     */
    protected static $_config;
    /**
     * @var string Current environment
     */
    protected $_env;
    /**
     * @var Zend_Controller_Front
     */
    protected $_front;
    /**
     * @var string Path to application root
     */
    protected $_root;
    /**
     * @var Zend_Log
     */
    protected $_log;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     * 
     * @param  string $env 
     * @param  string|null $root 
     * @return void
     */
    public function __construct ($env, $root = null)
    {
    	// setup autoloader
        require_once "Zend/Loader/Autoloader.php";
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
        $autoloader->suppressNotFoundWarnings(true);
        
        
        // root folder
        if (null === $root) $root = realpath(dirname(__FILE__)) . "/../..";
        $this->_root = $root;
        
        Zend_Registry::set("Root", $root);
        defined('ROOT') or define('ROOT', $root);
        // load environment
        $this->_setEnv($env);
        $this->initPhpConfig($env);
        
        // front controller
        $this->_front = Zend_Controller_Front::getInstance();
        
		// init routines        
        $this->initView();
        $this->initDb();
        $this->initAuth();
        $this->initHelpers();
        $this->initPlugins();
        $this->initControllers();
        $this->initMail();
    }

    /**
     * Initialize environment
     * 
     * @param  string $env 
     * @return void
     */
    protected function _setEnv ($env)
    {
        $this->_env = $env;
        date_default_timezone_set('America/New_York');
    }

    /**
     * Initialize Data bases
     * 
     * @return void
     */
    public function initPhpConfig ($env)
    {
        Initializer::$_config = new Zend_Config_Ini($this->_root . '/application/configs/application.ini', $env);
    }

    /**
     * Route startup
     * 
     * @return void
     */
    public function routeStartup (Zend_Controller_Request_Abstract $request)
    {
    }

    /**
     * Initialize data bases
     * 
     * @return void
     */
    public function initDb ()
    {
        $config = Initializer::$_config;
        $dbAdapter = Zend_Db::factory($config->database);
        $dbAdapter->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Db_Table_Abstract::setDefaultAdapter($dbAdapter);
        $registry = Zend_Registry::getInstance();
        $registry->configuration = $config;
        Zend_Registry::set('Config', $config);
        $registry->dbAdapter = $dbAdapter;
        Zend_Registry::set('dbAdapter', $dbAdapter);
    }

    /**
     * Initialize action helpers
     * 
     * @return void
     */
    public function initHelpers ()
    {
        $aclHelper = new Contabilidad_Acl_ActionHelper($this->_root);
        Zend_Controller_Action_HelperBroker::addHelper($aclHelper);
        
        $prefix = 'Contabilidad_Helper';
        $dir = $this->_root . '/application/views/helpers';
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->init();
        $view = $viewRenderer->view;
        $view->addHelperPath($dir, $prefix);
    }

    /**
     * Initialize view 
     * 
     * @return void
     */
    public function initView ()
    {
        // Bootstrap layouts
        //Zend_Layout::startMvc(array('layoutPath' => $this->_root . '/application/default/layouts' , 'layout' => 'main'));
        Zend_Layout::startMvc(array('layoutPath' => $this->_root . '/application/views/scripts' , 'layout' => 'layout'));
        //$this->_front->registerPlugin(new ActionSetup);
        $config = Initializer::$_config;
        $base = $config->base->toArray();
        defined('BASE_URL') or define('BASE_URL', $base['url']);
        defined('LINKS_URL') or define('LINKS_URL', $base['linksurl']);
    }

    /**
     * Initialize plugins 
     * 
     * @return void
     */
    public function initPlugins ()
    {}


    /**
     * Initialize Controller paths 
     * 
     * @return void
     */
    public function initControllers ()
    {
        $front = $this->_front;
        $front->throwExceptions(false);
        $front->addControllerDirectory($this->_root.'/application/modules/public/php/controllers', 'public');
        $front->addControllerDirectory($this->_root.'/application/modules/admin/php/controllers', 'admin');
        $front->setDefaultModule('public');
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler (array('controller' => 'error', 'action' => 'error', 'module' => 'public')));
    }

    public function initAuth ()
    {
        $registry = Zend_Registry::getInstance();
        $dbAdapter =  $registry->dbAdapter;
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName('user')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password');
        Zend_Registry::set('authAdapter', $authAdapter);
    }
    
    public function initMail()
    {
        $config = self::$_config;
        $params = $config->mail->smtp;
        $smtp = $params->toArray();
        $mailTransport = new Zend_Mail_Transport_Smtp($smtp['smtp'],$smtp['params']);
        Zend_Mail::setDefaultTransport($mailTransport);
    }
}
