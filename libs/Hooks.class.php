<?php
/**
 * @author eric.wzy@gmail.com
 * @version 1.1
 * @package phphooks
 * @category Plugins
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */

define ( 'PLUGINS_FOLDER', 'plugins/' );

class phphooks {

    var $addblock = array();
    var $addsideblock = array();
	var $add_Menu = array();
	var $add_User_Menu = array();
	/**
	 * plugins option data
	 * @var array
	 */
	var $plugins = array();
	
	/**
	 * UNSET means load all plugins, which is stored in the plugin folder. ISSET just load the plugins in this array.
	 * @var array
	 */
	var $active_plugins = NULL;
	
	/**
	 * all plugins header information array.
	 * @var array
	 */
	var $plugins_header = array();
	
	/**
	 * hooks data
	 * @var array
	 */
	var $hooks = array();
	
	/**
	 * register hook name/tag, so plugin developers can attach functions to hooks
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 */
	function set_hook($tag) {
		$this->hooks[$tag] = '';
	}
	
	/**
	 * register multiple hooks name/tag
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param array $tags. The name of the hooks.
	 */
	function set_hooks($tags) {
		foreach ( $tags as $tag ) {
			$this->set_hook ( $tag );
		}
	}
	
	/**
	 * write hook off
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 */
	function unset_hook($tag) {
		unset ( $this->hooks [$tag] );
	}
	
	/**
	 * write multiple hooks off
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param array $tags. The name of the hooks.
	 */
	function unset_hooks($tags) {
		foreach ($tags as $tag) {
			$this->developer_unset_hook($tag);
		}
	}
	
	/**
	 * load plugins from specific folder, includes *.plugin.php files
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $from_folder optional. load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
	 */
	function load_plugins($from_folder = PLUGINS_FOLDER) {
		
		if ($handle = @opendir ( $from_folder )) {
			
			while ( $file = readdir ( $handle ) ) {
				if (is_file ( $from_folder . $file )) {
					if (($this->active_plugins != NULL && in_array ( $file, $this->active_plugins )) && strpos ( $file, '.plugin.php' )) {
						require_once $from_folder . $file;
						$this->plugins [$file] ['file'] = $file;
					}
				} else if ((is_dir ( $from_folder . $file )) && ($file != '.') && ($file != '..')) {
					$this->load_plugins ( $from_folder . $file . '/' );
				}
			}
			
			closedir ( $handle );
		}
	
	}
	
	/**
	 * return the all plugins ,which is stored in the plugin folder, header information.
	 * 
	 * @package phphooks
	 * @since 1.1
	 * @param string $from_folder optional. load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
	 * @return array. return the all plugins ,which is stored in the plugin folder, header information.
	 */
	function get_plugins_header($from_folder = PLUGINS_FOLDER) {
		
		if ($handle = @opendir ( $from_folder )) {
			
			while ( $file = readdir ( $handle ) ) {
				if (is_file ( $from_folder . $file )) {
					if (strpos ( $from_folder . $file, '.plugin.php' )) {
						$fp = fopen ( $from_folder . $file, 'r' );
						// Pull only the first 8kiB of the file in.
						$plugin_data = fread ( $fp, 8192 );
						fclose ( $fp );
						
						preg_match ( '|Plugin Name:(.*)$|mi', $plugin_data, $name );
						preg_match ( '|Plugin URI:(.*)$|mi', $plugin_data, $uri );
						preg_match ( '|Version:(.*)|i', $plugin_data, $version );
						preg_match ( '|Description:(.*)$|mi', $plugin_data, $description );
						preg_match ( '|Author:(.*)$|mi', $plugin_data, $author_name );
						preg_match ( '|Author URI:(.*)$|mi', $plugin_data, $author_uri );
						
						foreach ( array ('name', 'uri', 'version', 'description', 'author_name', 'author_uri' ) as $field ) {
							if (! empty ( ${$field} ))
								${$field} = trim ( ${$field} [1] );
							else
								${$field} = '';
						}
						$plugin_data = array ('filename' => $file, 'Name' => $name, 'Title' => $name, 'PluginURI' => $uri, 'Description' => $description, 'Author' => $author_name, 'AuthorURI' => $author_uri, 'Version' => $version );
						$this->plugins_header [] = $plugin_data;
					}
				} else if ((is_dir ( $from_folder . $file )) && ($file != '.') && ($file != '..')) {
					$this->get_plugins_header ( $from_folder . $file . '/' );
				}
			}
			
			closedir ( $handle );
		}
		return $this->plugins_header;
	}
	
	/**
	 * attach custom function to hook
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 * @param string $function. The function you wish to be called.
	 * @param int $priority optional. Used to specify the order in which the functions associated with a particular action are executed.(range 0~20, 0 first call, 20 last call)
	 */
	function add_hook($tag, $function, $priority = 10) {
		if (! isset ( $this->hooks [$tag] )) {
			die ( "There is no such place ($tag) for hooks." );
		} else {
			$this->hooks [$tag] [$priority] [] = $function;
		}
	}
	
	/**
	 * check whether any function is attached to hook
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag The name of the hook.
	 */
	function hook_exist($tag) {
		return ( $this->hooks [$tag] == "") ? false : true;
	}
	
	/**
	 * execute all functions which are attached to hook, you can provide argument (or arguments via array)
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 * @param mix $args optional.The arguments the function accept (default none)
	 * @return optional.
	 */
	function execute_hook($tag, $args = '') {
		if (isset ( $this->hooks [$tag] )) {
			$these_hooks = $this->hooks [$tag];
			for($i = 0; $i <= 20; $i ++) {
				if (isset ( $these_hooks [$i] )) {
					foreach ( $these_hooks [$i] as $hook ) {
						// $args [] = $result;
						$result = call_user_func ( $hook, $args );
					}
				}
			}
			return $result;
		} else {
			die ( "There is no such place ($tag) for hooks." );
		}
	}
	
	/**
	 * filter $args and after modify, return it. (or arguments via array)
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $tag. The name of the hook.
	 * @param mix $args optional.The arguments the function accept to filter(default none)
	 * @return array. The $args filter result.
	 */
	function filter_hook($tag, $args = '') {
		$result = $args;
		if (isset ( $this->hooks [$tag] )) {
			$these_hooks = $this->hooks [$tag];
			for($i = 0; $i <= 20; $i ++) {
				if (isset ( $these_hooks [$i] )) {
					foreach ( $these_hooks [$i] as $hook ) {
						$args = $result;
						$result = call_user_func ( $hook, $args );
					}
				}
			}
			return $result;
		} else {
			die ( "There is no such place ($tag) for hooks." );
		}
	}
	
	/**
	 * register plugin data in $this->plugin
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $plugin_id. The name of the plugin.
	 * @param array $data optional.The data the plugin accessorial(default none)
	 */
	function register_plugin($plugin_id, $data = '') {
		foreach ( $data as $key => $value ) {
			$this->plugins [$plugin_id] [$key] = $value;
		}
	}
	/**
	 * Function of hook part
	 * by atmoner
	 */	
	###
	function set_title($id,$title=NULL) {
				$this->title->$id->id = $id;
				$this->title->$id = $title;
	}
	###
	function remove_title($id) {
		unset($this->title->$id);
	}
	###
	function add_block($id, $title, $content, $size, $p=5) {
				$this->addblock[$id]['id'] = $id;
                $this->addblock[$id]['title'] = $title;
                $this->addblock[$id]['content'] = $content;
                $this->addblock[$id]['size'] = $size;
                $this->addblock[$id]['prio'] = $p;		
	}
	###
	function remove_block($id) {
		unset($this->addblock[$id]);
	}
	###	
	function add_side_block($id, $title, $content, $p=5) {
		$this->addsideblock[$id]['id'] = $id;
                $this->addsideblock[$id]['title'] = $title;
                $this->addsideblock[$id]['content'] = $content;
                $this->addsideblock[$id]['prio'] = $p;		
	}
	###
	function remove_side_block($id) {
		unset($this->addsideblock[$id]);
	}
	###	
	function addMenu($id, $title, $url, $img, $p=5) {
				$this->add_Menu[$id]['id'] = $id;
                $this->add_Menu[$id]['title'] = $title;
                $this->add_Menu[$id]['url'] = $url;
                $this->add_Menu[$id]['img'] = $img;
                $this->add_Menu[$id]['prio'] = $p;	
	}
	###
	function remove_addMenu($id) {
		unset($this->add_Menu[$id]);
	}
	###	
	function addMenuLang($id, $title, $url, $img, $p=5) {
		$this->add_Menu_Lang[$id]['id'] = $id;
                $this->add_Menu_Lang[$id]['title'] = $title;
                $this->add_Menu_Lang[$id]['url'] = $url;
                $this->add_Menu_Lang[$id]['img'] = $img;
                $this->add_Menu_Lang[$id]['prio'] = $p;	
	}
	###
	function remove_addMenuLang($id) {
		unset($this->add_Menu_Lang[$id]);
	}
	###	
	function addUserMenu($id, $title, $url, $img, $p=5) {
				$this->add_User_Menu[$id]['id'] = $id;
                $this->add_User_Menu[$id]['title'] = $title;
                $this->add_User_Menu[$id]['url'] = $url;
                $this->add_User_Menu[$id]['img'] = $img;
                $this->add_User_Menu[$id]['prio'] = $p;	
	}
	###
	function remove_addUserMenu($id) {
		unset($this->add_User_Menu[$id]);
	}
	###################
	# Add addcontentPaste
	###################	
	function addcontentPaste($id, $content, $p=5) {
				$this->add_contentPaste[$id]['id'] = $id;
                $this->add_contentPaste[$id]['content'] = $content;
                $this->add_contentPaste[$id]['prio'] = $p;	
	}
	###
	function remove_addcontentPaste($id) {
		unset($this->add_contentPaste[$id]);
	}
	###################
	# Add content registration
	###################	
	function add_content_registration($id, $content, $p=5) {
				$this->addcontentregistration[$id]['id'] = $id;
                $this->addcontentregistration[$id]['content'] = $content;
                $this->addcontentregistration[$id]['prio'] = $p;	
	}
	###
	function remove_content_registration($id) {
		unset($this->addcontentregistration[$id]);
	}	
	###################
	# Add content login
	###################	
	function add_content_login($id, $content, $p=5) {
				$this->addcontentlogin[$id]['id'] = $id;
                $this->addcontentlogin[$id]['content'] = $content;
                $this->addcontentlogin[$id]['prio'] = $p;	
	}
	###
	function remove_content_login($id) {
		unset($this->addcontentlogin[$id]);
	} 
	###################
	# Add admin menu
	###################	
	function add_admin_menu($id, $title, $link, $p=5) {
				$this->linkplug[$id]['id'] = $id;
                $this->linkplug[$id]['title'] = $title;
                $this->linkplug[$id]['link'] = $link;
                $this->linkplug[$id]['prio'] = $p;	
	}
	###
	function remove_admin_menu($id) {
		unset($this->addcontentlogin[$id]);
	} 
	###################
	# Add js
	###################
	public function addJs($id, $file, $path, $p=5) {
			global $conf;
			if(!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $file))
				$file = $conf['baseurl'].'/'.$path.$file;
				
				$this->add_Js[$id]['id'] = $id;
                $this->add_Js[$id]['file'] = $file;
                $this->add_Js[$id]['prio'] = $p;
	}
	###
	public function remove_addJs($id) {
		unset($this->add_Js[$id]);
	}
	###################
	# Add css
	###################
	public function addCss($id, $file, $path, $p=5) {
			global $conf;
			if(!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $file))
				$file = $conf['baseurl'].'/'.$path.$file;
				
				$this->add_Css[$id]['id'] = $id;
                $this->add_Css[$id]['file'] = $file;
                $this->add_Css[$id]['prio'] = $p;
	}
	###
	public function remove_addCss($id) {
		unset($this->add_Css[$id]);
	}
	###################
	# Add page
	###################
    public function add_page( $plugin_name, $strip ) {       	
                global $smarty,$path;
                $get_page = (isset($_GET["page"])?$_GET["page"]:"");
                $pagesdir = dirname(__FILE__).'/../plugins/'.$plugin_name.'/php/';          
                $files = glob($pagesdir.'*.php');
                // var_dump($get_page);
                if ($get_page === $plugin_name) {
                        if(in_array($pagesdir.$plugin_name.'.php',$files)) {  
                                         require($pagesdir.$get_page.'.php');
                                         $smarty->display(dirname(__FILE__).'/../plugins/'.$plugin_name.'/html/'.$get_page.'.html');
                         } else 
                         		die('Check the configuration of your plugin!');
                 } else 
                 		die('404');
    } 
	###################
	# Add admin page
	###################
    function add_admin_page( $plugin_name, $strip ) {       	
                global $smarty,$path;
                $get_page = (isset($_GET["act"])?$_GET["act"]:"");
                $pagesdir = dirname(__FILE__).'/../plugins/'.$plugin_name.'/php/admin/';          
                $files = glob($pagesdir.'*.php');
                if ($get_page === $plugin_name) {
                        if(in_array($pagesdir.$plugin_name.'.php',$files)) {  
                                         require($pagesdir.$get_page.'.php');
                                         $smarty->display(dirname(__FILE__).'/../plugins/newpage/html/admin/'.$get_page.'.html');
                         }
                 }
    }       
}
?>
