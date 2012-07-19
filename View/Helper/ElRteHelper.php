<?php

class ElRteHelper extends Helper
{
	/**
	 * Helpers for ElRTEHElper
	 *
	 * @var array
	 */
    public $helpers = array(
	    'Html',
	    'Form'
    );

	/**
	 * Configuration
	 *
	 * @var array
	 */
	public $configs = array(
		'cssClass' => 'el-rte',
		'lang' => 'en',
		//'height' => 280,
		//'absoluteURLs' => 'false',
		'toolbar' => 'complete'
	);

	public function __construct(View $View, $options = array()) {
		parent::__construct($View, $options);

		$options = Configure::read('ElRTE.config');
		if (!empty($configs) && is_array($configs)) {
			$this->configs = array_merge($configs, $options);
		}
		$this->configs['sitebaseURL'] = 'http://'.$_SERVER['SERVER_NAME'].Router::url('/');
	}

    public function beforeRender() {
        //load editor
	    $this->Html->css("/el_rte/css/elrte.min", null, array('inline' => false));
        $this->Html->script("/el_rte/js/elrte.full", array('inline' => false));
	    $this->Html->script("/el_rte/js/i18n/elrte.".$this->configs['lang'], array('inline' => false));
		//load finder
	    $this->Html->css("/el_rte/css/elfinder.min", null, array('inline' => false));
        $this->Html->script("/el_rte/js/elfinder.min", array('inline' => false));
	    $this->Html->script("/el_rte/js/i18n/elfinder.".$this->configs['lang'], array('inline' => false));
    }
     
    public function render($fieldName, $options = array()){

	    $this->Form->setEntity($fieldName);
	    $id = ucfirst($this->Form->model()).ucfirst($this->Form->field());

	    $options = array_merge($this->configs, $options);

	    $lines = '';
	    foreach ($options as $option => $value) {
	  		$lines .= $option . " : '" . $value . "',\n";
	  	}
	    //Standard ugly finder init
	    // use it if modern way don't work
		//$lines .= "
        //    fmOpen : function(callback) {
        //        $('<div id=\"myelfinder\" />').elfinder({
        //            url : '".$this->Html->url(array( "plugin" => "el_rte", "controller"=>"el_finder", "action"=>"finder"))."',
        //            lang : 'en',
        //            dialog : { width : 800, modal : true, title : 'Select an Image' }, // open in dialog window
        //            closeOnEditorCallback : true, // close after file select
        //            editorCallback : callback
        //        });
        //    }\n
		//";

	    //modern way
	    //https://github.com/Studio-42/elFinder/wiki/Integration-with-elRTE-1.x
	    $lines .= "
	        absoluteURLs : false,
            cssfiles : ['http://".$_SERVER['SERVER_NAME'].Router::url('/')."el_rte/css/elrte-inner.css'],
			fmOpen: function(callback) {
				if (!dialog) {
					// create new elFinder
					dialog = $('<div />').dialogelfinder({
                        url : '".$this->Html->url(array( "plugin" => "el_rte", "controller"=>"el_finder", "action"=>"finder"))."',
	                    lang : 'en',
	                    dialog : { width : 800, modal : true, title : 'Select an Image' }, // open in dialog window
						commandsOptions: {
							getfile: {
								oncomplete : 'close' // close/hide elFinder
							}
						},
						getFileCallback: callback // pass callback to file manager
						//getFileCallback: function(file) { callback(file.path); } // pass callback to file manager
					});
				} else {
					// reopen elFinder
					dialog.dialogelfinder('open')
				}
			}\n
		";

        $js = "
            var dialog;
            $(document).ready(function(){
				var opts = { ".$lines." }
				$('#".$id."').elrte(opts);
            });
        ";
        return $this->Html->scriptBlock($js, array("inline"=> false));
    }  
}