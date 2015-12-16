<?php
$installer = $this;
$connection = $installer->getConnection();
$installer->startSetup();
$installer->getConnection()
	->addColumn($installer->getTable('athleteslideshow/revolution_slides'),
		'slide_bg',
		'VARCHAR(16) NOT NULL '
	);
$installer->endSetup();