<?php

	class UserMailchimpField extends \StructureField {

		static public $assets = [
			'css' => [
				'usermailchimp.css'
			]
		];

		public $lists = [];
		public $fields = [
			'list' => [
				'type' => 'select',
				'label' => 'Group'
			]
		];

		public function routes() {

			return [
				[
					'pattern' => 'add',
					'method' => 'get|post',
					'action' => 'add'
				],
				[
					'pattern' => '(:any)/delete',
					'method' => 'get|post',
					'action' => 'delete',
				]
			];
		}


		public function headline() {

			// get entries
			$entries = $this->entries();

			// check if limit is either null or the number of entries less than limit
			if (!$this->readonly && (is_null($this->limit) || (is_int($this->limit) && $entries->count() < $this->limit))) {

				$add = new Brick('a');
				$add->html('<i class="icon icon-left fa fa-plus-circle"></i>' . l('fields.structure.add'));
				$add->addClass('structure-add-button label-option');
				$add->data('modal', true);
				$add->attr('href', purl($this->model, 'field/' . $this->name . '/usermailchimp/add'));

			} else {
				$add = null;
			}

			// make sure there's at least an empty label
			if (!$this->label) {
				$this->label = '&nbsp;';
			}

			$label = BaseField::label();
			$label->addClass('structure-label');
			$label->append($add);

			return $label;

		}

		private function lists() {
			if (empty($this->lists)) {
				$MailChimp = new \DrewM\MailChimp\MailChimp(c::get("userManager.mailchimp"));


				$start = 0;
				do {
					$listArray = $MailChimp->get('lists', ['offset' => $start, 'count' => 100])['lists'];
					foreach ($listArray as $list) {
						$this->lists[$list['id']] = $list['name'];
					}
					$start += 100;
				} while (count($listArray) > 99);

			}

			return $this->lists;
		}

		public function entries() {

			$MailChimp = new \DrewM\MailChimp\MailChimp(c::get("userManager.mailchimp"));
			$matches = $MailChimp->get('search-members', ['query' => $this->model()->email()])['exact_matches']['members'];
			$data = new \Collection;
			foreach ($matches as $match) {
				if ($match['status'] == 'subscribed') {
					$obj = new \obj(['list' => $this->lists()[$match['list_id']], 'id' => $match['list_id']]);
					$data->append($match['list_id'], $obj);
				}
			}

			return $data;
		}

		public function entry($data) {

			$html = array();
			foreach ($this->fields as $name => $field) {
				if (isset($data->$name)) {
					$html[] = $data->$name;
				}
			}

			return implode('<br>', $html);
		}

		public function content() {
			return tpl::load(__DIR__ . DS . 'template.php', ['field' => $this]);
		}

		public function url($action) {
			return purl($this->model(), 'field/' . $this->name() . '/usermailchimp/' . $action);
		}
	}