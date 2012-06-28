<?php
App::uses('ElRteAppController', 'ElRte.Controller');

class ElFinderController extends ElRteAppController {


	public function admin_finder(){
		App::import('Vendor', 'ElRte.elFinder/elFinderConnector.class');
		App::import('Vendor', 'ElRte.elFinder/elFinder.class');
		App::import('Vendor', 'ElRte.elFinder/elFinderVolumeDriver.class');
		App::import('Vendor', 'ElRte.elFinder/elFinderVolumeLocalFileSystem.class');

		$opts = array(
			// 'debug' => true,
			'roots' => array(
				array(
					'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
					'path'          => APP.DS.'webroot'.DS.'files'.DS,         // path to files (REQUIRED)
					'URL'           => 'files/', // URL to files (REQUIRED)
					//'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
				    'dotFiles'     => false,
				    'dirSize'      => true,
				    'fileMode'     => 0644,
					'dirMode'      => 0755,
					'mimeDetect'   => 'internal',
					'imgLib'       => 'auto',
					'tmbDir'       => '.tmb'				)
			)
		);

		// run elFinder
		$connector = new ElFinderConnector(new ElFinder($opts));
		$connector->run();
	}

}
