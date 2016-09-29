<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 24/9/2016
	 * Time: 12:06 PM
	 */

	namespace lcd344\Panel\Controllers;

	use Error;
	use Exception;
	use Kirby\Panel\Controllers\Base;
	use Kirby\Panel\Form;
	use lcd344\Panel\Collections\Users;
	use lcd344\Panel\Models\User;
	use lcd344\Panel\Stubs\UserMailer;
	use lcd344\Panel\Stubs\View;
	use Router;

	class UserMailerController extends Base {

		public function __construct() {
			Form::$root = array(
				'default' => panel()->roots->fields,
				'custom' => panel()->kirby->roots()->fields()
			);
		}

		public function index($username) {

			$self = $this;
			$user = new User($username);
			$mailer = new UserMailer($user);
			$form = $mailer->form(function ($form) use ($self, $user) {

				$data = $form->serialize();
				try {
					$mailer = $user->createMail($data['driver']);
					if ($data['driver'] == 'phpmailer') {
						if(trim($data['cc'] != '')){
							$mailer->cc(explode(',', $data['cc']));
						}
						if(trim($data['bcc'] != '')){
							$mailer->bcc(explode(',', $data['bcc']));
						}
						if(isset($_FILES['file'])){
							for($i = 0; $i < count($_FILES['file']['name']);$i++){
								$mailer->attach([$_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]]);
							}
						}
					}
					if($mailer->send($data['subject'], kirbytext($data['body']))){
						$self->notify('Email Sent To ' . $user->username());
					} else {
						$self->alert("Could not send email.");
					}

				} catch (Error $e) {
					$self->alert($e->getMessage());
				}
			});

			$form->action(panel()->urls()->index() . '/userMailer/' . $username);
			$form->attr("id", "dropzoneForm");
			$form->attr("class", "dropzone");
			$form->attr("enctype", "multipart/form-data");
			$form->attr("style", "padding-top: 20px");
			echo $this->screen('userMailer/index', $mailer, array(
				'user' => $user,
				'form' => $form,
				'users' => new Users()
			));
		}

		public function fields($username, $fieldName, $fieldType, $path) {

			$user = new User($username);
			$mailer = new UserMailer($user);
			$form = $mailer->form(function () {
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

					$controller = new $controllerName($mailer, $field);

					echo call([$controller, $route->action()], $route->arguments());

				}

			} else {
				throw new Exception(l('fields.error.route.invalid'));
			}

		}

		public function view($file, $data = array()) {
			return new View($file, $data);
		}
	}