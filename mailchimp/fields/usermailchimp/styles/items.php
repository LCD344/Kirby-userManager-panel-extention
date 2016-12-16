<?php foreach ($field->entries() as $entry) { ?>
	<div class="structure-entry">
		<div class="structure-entry-content text usermailchimp-entry">
			<?= $field->entry($entry) ?>
			<a data-modal class="btn btn-with-icon structure-delete-button"
			   href="<?php __($field->url($entry->id() . '/delete')) ?>">
				<?php i('trash-o', 'left') . _l('fields.structure.delete') ?>
			</a>
		</div>
		<?php if (!$field->readonly()) { ?>
		<?php } ?>
	</div>
<?php } ?>