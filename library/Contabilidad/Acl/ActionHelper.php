<?php
/** Zend_Controller_Action_Helper_Abstract */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class Contabilidad_Acl_ActionHelper extends Zend_Controller_Action_Helper_Abstract
{
	protected $_action;
	/**
	 * Enter description here...
	 *
	 * @var Zend_Auth
	 */
	protected $_root;
	protected $_controller;
	protected $_module;
	protected $auth;
	protected $_view;
	
	public function __construct($root)
	{
	    $this->auth = Zend_Auth::getInstance();
	    $this->_root = $root;
	}
	
	/**
	* Hook into action controller initialization
	* @return void
	*/
	public function init()
	{
		$this->_action = $this->getActionController();
		$this->_controller = $this->_action->getRequest()
			->getControllerName(); 
		$this->_module = $this->_action->getRequest()
			->getModuleName();
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $this->view = $viewRenderer->view;
        $this->view->isLogged = $this->auth->hasIdentity();
	}
	
    public function preDispatch()
    {
        $request = $this->_action->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();
        $helper = $this->_action->getHelper("Redirector");

        $view = $this->view;
        $view->action = $action;
        $view->module = $module;
        $view->controller = $controller;
        if($module == "admin"){
            Zend_Layout::startMvc(array('layoutPath' => $this->_root . '/application/views/scripts' , 'layout' => 'admin-layout'));
        }
        if($view->isLogged) {
            if($module == "public" && $action != "logout" && $controller != "error" && $controller != "services" && $action != "setpass"){
                $helper->direct("index", "categorias", "admin");
            } elseif($module == "admin" && $controller == "index" && $action == "index"){
                $helper->direct("index", "categorias", "admin");
            }
        } else {
            if($module == "admin" && $controller != "index"){
                $helper->direct("index", "index", "public");
            }
        }
    }
}