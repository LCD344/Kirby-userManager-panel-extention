<?php
namespace lcd344\Panel\Controllers;

use c;
use Exception;
use Kirby\Panel\Controllers\Base;
use Kirby\Panel\Form;
use lcd344\Mailer;
use lcd344\Panel\Collections\Users;
use lcd344\Panel\Models\User;
use lcd344\Panel\ServerSideDatabaseDatatable;
use lcd344\Panel\ServerSideFileDatatable;
use lcd344\Panel\Stubs\NewUser;
use lcd344\Panel\Stubs\View;
use Router;

class UserManagementController extends Base {

	public function __construct() {
		Form::$root = array(
			'default' => panel()->roots->fields,
			'custom' => panel()->kirby->roots()->fields()
		);
	}

	public function index() {

		$users = new Users();
		$admin = panel()->user()->isAdmin();

		/** @noinspection PhpParamsInspection */
		$users = $users->filter(function ($user) {
			return $user->ui()->read();
		});


		$fields = c::get("userManager.fields", [
			"Avatar" => "Avatar",
			"Username" => ["name" => "Username", 'action' => "edit", 'element' => "strong", 'class' => "item-title"],
			"Email" => ['name' => "Email", 'action' => ((class_exists(Mailer::class)) ? "email" : "edit")],
			"Role" => "Role"
		]);

		echo $this->screen('UserManager/index', $users, [
			'users' => $users,
			'admin' => $admin,
			'fields' => $fields
		]);
	}

	public function add() {

		if (panel()->user()->ui()->create() === false) {
			throw new PermissionsException();
		}

		$self = $this;
		$form = $this->form(__DIR__ . DS . '../Forms/user.php', null, function ($form) use ($self) {

			$form->validate();

			if (!$form->isValid()) {
				return false;
			}

			$data = $form->serialize();

			try {
				$users = new Users();
				$users->create($data);
				$self->notify(':)');
				$self->redirect('userManagement');
			} catch (Exception $e) {
				$self->alert($e->getMessage());
			}

		});

		$form->cancel("userManagement");
		$form->action(panel()->urls()->index() . '/userManagement/add');
		echo $this->screen('UserManager/edit', new NewUser(), [
			'user' => null,
			'form' => $form,
			'writable' => is_writable(kirby()->roots()->accounts()),
			'uploader' => null
		]);

	}

	public function edit($username) {

		$self = $this;
		$user = new User($username);

		if ($user->ui()->read() === false) {
			throw new PermissionsException();
		}

		$form = $this->form(__DIR__ . DS . '../Forms/user.php', $user, function ($form) use ($user, $self) {

			$form->validate();

			if (!$form->isValid()) {
				return false;
			}

			$data = $form->serialize();

			try {
				$user->update($data);
				$self->notify(':)');
				$url = $user->uri('edit');
				$self->redirect($url);
			} catch (Exception $e) {
				$self->alert($e->getMessage());
			}

		});

		$form->cancel("userManagement");
		$form->action(panel()->urls()->index() . "/userManagement/{$username}/edit");

		echo $this->screen('UserManager/edit', $user, array(
			'user' => $user,
			'form' => $form,
			'writable' => is_writable(kirby()->roots()->accounts()),
			'uploader' => $this->snippet('uploader', array(
				'url' => $user->url('avatar'),
				'accept' => 'image/jpeg,image/png,image/gif',
				'multiple' => false
			))
		));
	}

	public function delete($username) {

		$user = new User($username);
		$self = $this;

		if ($user->ui()->delete() === false) {
			throw new PermissionsException();
		}

		$form = $user->form('delete', function ($form) use ($user, $self) {
			try {
				$user->delete();
				$self->notify(':)');
				$self->redirect('userManagement');
			} catch (Exception $e) {
				$form->alert($e->getMessage());
			}

		});

		$form->action(panel()->urls()->index() . "/userManagement/{$username}/delete");
		echo $this->modal('UserManager/delete', compact('form'));


	}

	public function view($file, $data = array()) {
		return new View($file, $data);
	}

	public function fields($username, $fieldName, $fieldType, $path) {

		$user = new User($username);
		$form = $user->form('user', function () {
		});

		$field = $form->fields()->$fieldName;


		if (!$field or $field->type() !== $fieldType) {
			throw new Exception('Invalid field');
		}

		$routes = $field->routes();
		$router = new Router($routes);

		if ($route = $router->run($path)) {

			if (is_callable($route->action()) and is_a($route->action(), 'Closure')) {
				return call($route->action(), $route->arguments());
			} else {

				$controllerFile = $field->root() . DS . 'controller.php';
				$controllerName = $fieldType . 'FieldController';

				if (!file_exists($controllerFile)) {
					throw new Exception(l('fields.error.missing.controller'));
				}

				require_once($controllerFile);

				if (!class_exists($controllerName)) {
					throw new Exception(l('fields.error.missing.class'));
				}

				$controller = new $controllerName($user, $field);
				$result = call([$controller, $route->action()], $route->arguments());

				$response = $result->content();
				$response = str_replace('class=\"form\"', 'class=\"form\" ' . 'action=\"' . $_SERVER['REQUEST_URI'] . '\"', $response);

				echo \Response::json($response);

				/*					$form = $result->_data['content']->_data['form'];
									$form->action($_SERVER['REQUEST_URI']);*/
				exit();

			}

		} else {
			throw new Exception(l('fields.error.route.invalid'));
		}

	}

	public function users(){

		$columns = c::get("userManager.fields", [
			"Avatar" => "Avatar",
			"Username" => ["name" => "Username", 'action' => "edit", 'element' => "strong", 'class' => "item-title"],
			"Email" => ['name' => "Email", 'action' => ((class_exists(Mailer::class)) ? "email" : "edit")],
			"Role" => "Role"
		]);

		if(c::get('userManager.database', false)){
			$response = ServerSideDatabaseDatatable::search($_GET,$columns);
		} else {
			$users = new Users();
			$users = $users->filter(function ($user) {
				return $user->ui()->read();
			});
			$response = ServerSideFileDatatable::search($users,$_GET,$columns);
		}

		echo \Response::json($response);
	}

}

?>