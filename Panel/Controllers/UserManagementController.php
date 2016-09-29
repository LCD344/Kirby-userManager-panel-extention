<?php
	namespace lcd344\Panel\Controllers;

	use c;
	use Exception;
	use Kirby\Panel\Controllers\Base;
	use Kirby\Panel\Form;
	use lcd344\Mailer;
	use lcd344\Panel\Collections\Users;
	use lcd344\Panel\Models\User;
	use lcd344\Panel\Stubs\NewUser;
	use lcd344\Panel\Stubs\View;

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

			$fields = c::get("userManager.fields",[
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

			if (!panel()->user()->isAdmin()) {
				$this->redirect('users');
			}
			$self = $this;

			$form = $this->form('users/user', null, function ($form) use ($self) {

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

			if (!panel()->user()->isAdmin() and !$user->isCurrent()) {
				$this->redirect('users');
			}

			$form = $user->form('user', function ($form) use ($user, $self) {

				$form->validate();

				if (!$form->isValid()) {
					return false;
				}

				$data = $form->serialize();

				try {
					$user->update($data);
					$self->notify(':)');
					$self->redirect($user, 'edit');
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

			if (!panel()->user()->isAdmin() and !$user->isCurrent()) {
				return $this->modal('error', array(
					'headline' => l('error'),
					'text' => l('users.delete.error.rights'),
					'back' => purl('userManagement')
				));
			} else {

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

		}

		public function view($file, $data = array()) {
			return new View($file, $data);
		}

	}

	?>