<?php

	return function ($model, $structure) {
		$MailChimp = new \DrewM\MailChimp\MailChimp(c::get("userManager.mailchimp"));

		$lists = [];
		$start = 0;
		do{
			$listArray = $MailChimp->get('lists',['offset' => $start,'count' => 100])['lists'];
			foreach ($listArray as $list){
				$lists[$list['id']] = $list['name'];
			}
			$start += 100;
		} while(count($listArray) > 99);

		$fields = [
			'list' => [
				'label' => 'Group',
				'type' => 'select',
				'required' => true,
				'options' => $lists
			]
		];

		$form = new Kirby\Panel\Form($fields, array(), $structure->field());
		$form->cancel($model);
		$form->buttons->submit->value = l('add');

		return $form;
	};