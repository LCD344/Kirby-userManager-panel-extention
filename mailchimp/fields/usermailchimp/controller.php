<?php

	class UserMailchimpFieldController extends \Kirby\Panel\Controllers\Field {
		public function add() {

			$self = $this;
			$field = $this->field();
			$model = $this->model();
			$structure = $this->structure($model);

			// abort if the field already has too many items or is readonly
			if ($field->readonly || (!is_null($field->limit) && $field->entries()->count() >= $field->limit)) {
				return $this->modal('error', array(
					'text' => l('fields.structure.max.error')
				));
			}

			$modalsize = $this->field()->modalsize();
			$form = $this->form('add', array($model, $structure), function ($form) use ($model, $structure, $self) {

				$data = $form->serialize();
				$list = $data['list'];
				$MailChimp = new \DrewM\MailChimp\MailChimp(c::get("userManager.mailchimp"));
				$subscriber_hash = $MailChimp->subscriberHash($model->email());

				$result = $MailChimp->put("lists/$list/members/$subscriber_hash", [
					'email_address' => $model->email(),
					'status'        => 'subscribed',
				]);

				if(is_numeric($result['status'])){
					$self->alert($result['detail']);
				} else {
					$self->notify("User Added To List");
				}

				$self->redirect($model);


			});

			return $this->modal('add', compact('form', 'modalsize'));

		}

		public function delete($entryId) {

			$self = $this;
			$model = $this->model();
			$structure = $this->structure($model);

			$form = $this->form('delete', $model, function () use ($self, $model, $structure, $entryId) {

				$MailChimp = new \DrewM\MailChimp\MailChimp(c::get("userManager.mailchimp"));
				$subscriber_hash = $MailChimp->subscriberHash($model->email());

				$MailChimp->delete("/lists/$entryId/members/$subscriber_hash");

				$self->notify("User Removed From List");
				$self->redirect($model);
			});

			return $this->modal('delete', compact('form'));

		}

		protected function structure($model) {
			return $model->structure()->forField($this->fieldname());
		}
	}