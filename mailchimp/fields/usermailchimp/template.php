<div class="structure"
	 data-field="structure"
	 data-sortable="false"
	 data-style="items">

	<?php echo $field->headline() ?>

	<div class="structure-entries">

		<?php if (!$field->entries()->count()) { ?>
			<div class="structure-empty">
				<?php _l('fields.structure.empty') ?> <a data-modal class="structure-add-button"
														 href="<?php __($field->url('add')) ?>"><?php _l('fields.structure.add.first') ?></a>
			</div>
		<?php } else {
			require(__DIR__ . DS . 'styles' . DS . 'items.php');
		} ?>
	</div>

</div>