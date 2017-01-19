<div class="bars bars-with-sidebar-left cf">

    <div class="sidebar sidebar-left">

        <a class="sidebar-toggle" href="#sidebar"
           data-hide="<?php _l('options.hide') ?>"><span><?php _l('options.show') ?></span></a>

        <div class="sidebar-content section">

			<?php if ($user and $writable){ ?>
                <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
                    <span class="hgroup-title"><?php _l('users.form.options.headline') ?></span>
                </h2>

                <ul class="nav nav-list sidebar-list">

                    <li>
						<?php if ($user->isCurrent()){ ?>
                            <a href="<?= purl('logout') ?>">
								<?php i('power-off', 'left') . _l('logout') ?>
                            </a>
						<?php } else { ?>
                            <a href="<?= ((class_exists(lcd344\Mailer::class) && \c::get("userManager.mailer", true)) ? purl("userMailer/{$user->username()}") : "mailto:{$user->email()}") ?>">
								<?php i('envelope-square', 'left') . _l('users.form.options.message') ?>
                            </a>
						<?php } ?>
                    </li>

                    <li>
						<?php if ($user->ui()->delete()){ ?>
                            <a data-modal title="#" data-shortcut="#" href="<?php __($user->url('delete')) ?>">
								<?php i('trash-o', 'left') . _l('users.form.options.delete') ?>
                            </a>
						<?php } ?>
                    </li>

                </ul>

				<?php if ($user->avatar()->ui()->active()){ ?>

                    <h2 class="hgroup hgroup-single-line<?php e(!$user->avatar()->exists(), ' hgroup-compressed') ?> cf">
                        <span class="hgroup-title"><?php _l('users.form.avatar.headline') ?></span>
                    </h2>

					<?php if ($user->avatar()->exists()){ ?>
                        <div class="field">
							<?php if ($user->avatar()->ui()->replace()){ ?>
                                <a data-upload class="avatar avatar-large" href="#upload">
                                    <img src="<?= $user->avatar(150)->url() ?>">
                                </a>
							<?php } else { ?>
                                <span class="avatar avatar-large">
                                    <img src="<?= $user->avatar(150)->url() ?>">
                                </span>
							<?php } ?>
                        </div>
					<?php } ?>

                    <ul class="nav nav-list sidebar-list">

						<?php if ($user->avatar()->exists()){ ?>

							<?php if ($user->avatar()->ui()->replace()){ ?>
                                <li>
                                    <a data-upload href="#upload">
										<?php i('pencil', 'left') . _l('users.form.avatar.replace') ?>
                                    </a>
                                </li>
							<?php } ?>

							<?php if ($user->avatar()->ui()->delete()){ ?>
                                <li>
                                    <a data-modal href="<?php __($user->url('avatar/delete')) ?>">
										<?php i('trash-o', 'left') . _l('users.form.avatar.delete') ?>
                                    </a>
                                </li>
							<?php } ?>

						<?php } else if ($user->avatar()->ui()->upload()){ ?>
                            <li>
                                <a data-upload href="#upload">
									<?php i('cloud-upload', 'left') . _l('users.form.avatar.upload') ?>
                                </a>
                            </li>
						<?php } ?>

                    </ul>

				<?php } ?>

			<?php } else if ($user and !$writable){ ?>
                <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
                    <span class="hgroup-title"><?php _l('users.form.options.headline') ?></span>
                </h2>

                <a class="btn btn-with-icon" href="<?php _u('users') ?>">
					<?php i('arrow-circle-left', 'left') . _l('users.form.back') ?>
                </a>

			<?php } else {  ?>
                <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
                    <span class="hgroup-title"><?php _l('users.index.add') ?></span>
                </h2>

                <a class="btn btn-with-icon" href="<?php _u('users') ?>">
					<?php i('arrow-circle-left', 'left') . _l('users.form.back') ?>
                </a>
			<?php } ?>

        </div>
    </div>

    <div class="mainbar">
        <div class="section">
			<?php if (!$writable){ ?>
                <div class="form">
                    <h2 class="hgroup hgroup-single-line hgroup-compressed cf">
                        <span class="hgroup-title"><?php _l('users.form.error.permissions.title') ?></span>
                    </h2>
                    <div class="text">
                        <p><?php _l('users.form.error.permissions.text') ?></p>
                    </div>
                    <div><a href="<?php __(url::current()) ?>"
                            class="btn btn-rounded"><?php _l('pages.show.error.permissions.retry') ?></a></div>
                </div>
			<?php } else{ ?>
				<?= $form ?>
			<?php } ?>
        </div>
    </div>

</div>

<?= $uploader ?>