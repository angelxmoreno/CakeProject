<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $navLinks = array(
	    'Home' => array(
		'url' => '/'
	    ),
	    'About' => array(
		'url' => array('admin' => false, 'plugin' => null, 'controller' => 'pages', 'action' => 'display', 'about'),
	    ),
	    'Contact' => array(
		'url' => array('admin' => false, 'plugin' => null, 'controller' => 'pages', 'action' => 'contact'),
	    ),
	    'Dashboard' => array(
		'url' => array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'dashboard'),
		'auth' => true,
	    ),
	    'Log Out' => array(
		'url' => array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'logout'),
		'auth' => true,
	    ),
	    'Log In' => array(
		'url' => array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'login'),
		'auth' => false,
	    ),
	    'Register' => array(
		'url' => array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'add'),
		'auth' => false,
	    )
	);

	/**
	 * Helpers
	 *
	 * @var array
	 */
	public $helpers = array(
	    'Session',
	    'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
	    'Form' => array('className' => 'TwitterBootstrap.BootstrapForm'),
	    'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
	);

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
	    'Auth',
	    'Session',
	    'Cookie',
	    'Security',
	    'DebugKit.Toolbar',
	    'Users.RememberMe',
	    'RequestHandler',
	    'Crud.Crud' => array(
		'actions' => array('index', 'add', 'edit', 'view'),
		'listeners' => array('Api', 'ApiFieldFilter', 'ApiPagination'),
	    ),
	);

	/**
	 * List of components which can handle action invocation
	 * @var array
	 */
	public $dispatchComponents = array();

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->_buildAuth();
		$this->_restoreLoginFromCookie();
		$this->set('navLinks', $this->navLinks);
	}

	/**
	 * Setup Authentication Component
	 *
	 * @return void
	 */
	protected function _buildAuth() {
		$this->Auth->allow('display');
		$this->Auth->authenticate = array(
		    'Form' => array(
			'fields' => array(
			    'username' => 'email',
			    'password' => 'password'),
			'userModel' => 'AppUser',
			'scope' => array(
			    'AppUser.active' => 1,
			    'AppUser.email_verified' => 1)));

		$this->Auth->loginRedirect = array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'dashboard');
		$this->Auth->logoutRedirect = array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'login');
		$this->Auth->loginAction = array('admin' => false, 'plugin' => null, 'controller' => 'app_users', 'action' => 'login');
	}

	protected function _restoreLoginFromCookie() {
		$this->Cookie->name = 'Users';
		$cookie = $this->Cookie->read('rememberMe');
		if (!empty($cookie)) {
			$this->request->data['User'][$this->Auth->fields['username']] = $cookie[$this->Auth->fields['username']];
			$this->request->data['User'][$this->Auth->fields['password']] = $cookie[$this->Auth->fields['password']];
			$this->Auth->login();
		}
	}

	/**
	 * Dispatches the controller action. Checks that the action exists and isn't private.
	 *
	 * If Cake raises MissingActionException we attempt to execute Crud
	 *
	 * @param CakeRequest $request
	 * @return mixed The resulting response.
	 * @throws PrivateActionException When actions are not public or prefixed by _
	 * @throws MissingActionException When actions are not defined and scaffolding and CRUD is not enabled.
	 */
	public function invokeAction(CakeRequest $request) {
		try {
			return parent::invokeAction($request);
		} catch (MissingActionException $e) {
			// Check for any dispatch components
			if (!empty($this->dispatchComponents)) {
				// Iterate dispatchComponents
				foreach ($this->dispatchComponents as $component => $enabled) {
					// Skip them if they aren't enabled
					if (empty($enabled)) {
						continue;
					}

					// Skip if isActionMapped isn't defined in the Component
					if (!method_exists($this->{$component}, 'isActionMapped')) {
						continue;
					}

					// Skip if the action isn't mapped
					if (!$this->{$component}->isActionMapped($request->params['action'])) {
						continue;
					}

					// Skip if executeAction isn't defined in the Component
					if (!method_exists($this->{$component}, 'executeAction')) {
						continue;
					}

					// Execute the callback, can return CakeResponse object
					return $this->{$component}->executeAction();
				}
			}

			// No additional callbacks, re-throw the normal Cake exception
			throw $e;
		}
	}

}
