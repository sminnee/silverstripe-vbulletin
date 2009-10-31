<?php

/**
 * Role to apply to Member to add extra fields for VBulletin integration
 */
class VBulletinRole extends DataObjectDecorator {
	public function extraStatics() {
		return array(
			'db' => array(
				'VBulletinUserID' => 'Int',
				'VBulletinUsername' => 'Varchar',
			)
		);
	}
}
	