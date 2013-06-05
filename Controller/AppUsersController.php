<?php
App::uses('UsersController', 'Users.Controller');

class AppUsersController extends UsersController {

	public $name = 'AppUsers';
	public $plugin = null;
	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->User = ClassRegistry::init('AppUser');
		$this->set('model', 'AppUser');
	}

	/**
	 * Renders the views in app/Plugin/Users/View/Users when a ctp file is not
	 * found in app/View/AppUsers.
	 *
	 * @todo make sure to transverse through the various defined paths including
	 * themes rather than assuming the View paths directory.
	 *
	 * @param string $view View to use for rendering
	 * @param string $layout Layout to use
	 * @return CakeResponse A response object containing the rendered view.
	 */
	public function render($view = null, $layout = null) {
		if (is_null($view)) {
			$view = $this->action;
		}
		$prefix = '';
		if (!file_exists(APP . 'View' . DS . $this->viewPath . DS . $view . '.ctp')) {
			$prefix = 'Users.Users/';
		}
		return parent::render($prefix . $view, $layout);
	}

	/**
	 * Setup Authentication Component
	 *
	 * @return void
	 */
	protected function _setupAuth() {
		$this->Auth->allow('add', 'reset', 'verify', 'logout', 'reset_password', 'login');
		if (!is_null(Configure::read('Users.allowRegistration')) && !Configure::read('Users.allowRegistration')) {
			$this->Auth->deny('add');
		}
		if ($this->request->action == 'register') {
			$this->Components->disable('Auth');
		}
	}
}