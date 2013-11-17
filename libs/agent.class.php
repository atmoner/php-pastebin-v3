<?php
/*
 * @author		Diego Saint Esteben <http://www.taringa.net/perfil/dii3g0>
 * @file		agent.class.php
 * @version		1.0.0
 */
 
class Agent {
	/**
	* Useragent
	*/
	private $__user_agent;

	/**
	* private property
	*/
	private $_isbrowser	= FALSE;
	private $_ismobile	= FALSE;
	private $_isrobot	= FALSE;
 
	/**
	* public property
	*/
	public  $platform			= 'Unknown';
	public  $browser			= 'Unknown';
	public  $mobile				= 'Unknown';
	public  $robot				= 'Unknown';
	public  $version			= '0.0.0';
	public	$user_IP			= '0.0.0.0';

	/**
	* array of useragents
	*/
	private $_useragents	= array('platforms' => array(
								'windows nt 4.0'	=> 'Windows NT 4.0',
								'windows nt 5.0'	=> 'Windows 2000',
								'windows nt 5.1'	=> 'Windows XP',
								'windows nt 5.2'	=> 'Windows 2003',
								'windows nt 6.0'	=> 'Windows Vista/Longhorn',
								'windows nt 6.1'	=> 'Windows 7',
								'winnt4.0'			=> 'Windows NT 4.0',
								'winnt 4.0'			=> 'Windows NT',
								'winnt'				=> 'Windows NT',
								'windows 98'		=> 'Windows 98',
								'win98'				=> 'Windows 98',
								'windows 95'		=> 'Windows 95',
								'win95'				=> 'Windows 95',
								'windows'			=> 'Unknown Windows OS',
								'os x'				=> 'Mac OS X',
								'ppc mac'			=> 'Power PC Mac',
								'freebsd'			=> 'FreeBSD',
								'ppc'				=> 'Macintosh',
								'linux'				=> 'Linux',
								'debian'			=> 'Debian',
								'sunos'				=> 'Sun Solaris',
								'beos'				=> 'BeOS',
								'apachebench'		=> 'ApacheBench',
								'aix'				=> 'AIX',
								'irix'				=> 'Irix',
								'osf'				=> 'DEC OSF',
								'hp-ux'				=> 'HP-UX',
								'netbsd'			=> 'NetBSD',
								'bsdi'				=> 'BSDi',
								'openbsd'			=> 'OpenBSD',
								'gnu'				=> 'GNU/Linux',
								'unix'				=> 'Unknown Unix OS'
							),
							'browsers' => array(
								'opera'				=> 'Opera',
								'msie'				=> 'Internet Explorer',
								'internet explorer'	=> 'Internet Explorer',
								'chrome'			=> 'Google Chrome',
								'firefox'			=> 'Firefox',
								'shiira'			=> 'Shiira',
								'chimera'			=> 'Chimera',
								'phoenix'			=> 'Phoenix',
								'firebird'			=> 'Firebird',
								'camino'			=> 'Camino',
								'netscape'			=> 'Netscape',
								'omniWeb'			=> 'OmniWeb',
								'safari'			=> 'Safari',
								'mozilla'			=> 'Mozilla',
								'konqueror'			=> 'Konqueror',
								'icab'				=> 'iCab',
								'lynx'				=> 'Lynx',
								'links'				=> 'Links',
								'hotjava'			=> 'HotJava',
								'amaya'				=> 'Amaya',
								'ibrowse'			=> 'IBrowse'
							),
							'mobiles' => array(
								'mobileexplorer'	=> 'Mobile Explorer',
								'openwave'			=> 'Open Wave',
								'opera mini'		=> 'Opera Mini',
								'operamini'			=> 'Opera Mini',
								'elaine'			=> 'Palm',
								'palmsource'		=> 'Palm',
								'digital paths'		=> 'Palm',
								'avantgo'			=> 'Avantgo',
								'xiino'				=> 'Xiino',
								'palmscape'			=> 'Palmscape',
								'motorola'			=> 'Motorola',
								'nokia'				=> 'Nokia',
								'palm'				=> 'Palm',
								'iphone'			=> 'Apple iPhone',
								'ipod'				=> 'Apple iPod Touch',
								'sony'				=> 'Sony Ericsson',
								'ericsson'			=> 'Sony Ericsson',
								'blackberry'		=> 'BlackBerry',
								'cocoon'			=> 'O2 Cocoon',
								'blazer'			=> 'Treo',
								'lg'				=> 'LG',
								'amoi'				=> 'Amoi',
								'xda'				=> 'XDA',
								'mda'				=> 'MDA',
								'vario'				=> 'Vario',
								'htc'				=> 'HTC',
								'samsung'			=> 'Samsung',
								'sharp'				=> 'Sharp',
								'sie-'				=> 'Siemens',
								'alcatel'			=> 'Alcatel',
								'benq'				=> 'BenQ',
								'ipaq'				=> 'HP iPaq',
								'mot-'				=> 'Motorola',
								'playstation portable'	=> 'PlayStation Portable',
								'hiptop'			=> 'Danger Hiptop',
								'nec-'				=> 'NEC',
								'panasonic'			=> 'Panasonic',
								'philips'			=> 'Philips',
								'sagem'				=> 'Sagem',
								'sanyo'				=> 'Sanyo',
								'spv'				=> 'SPV',
								'zte'				=> 'ZTE',
								'sendo'				=> 'Sendo',
								'symbian'			=> 'Symbian',
								'symbianos'			=> 'SymbianOS',
								'elaine'			=> 'Palm',
								'palm'				=> 'Palm',
								'series60'			=> 'Symbian S60',
								'windows ce'		=> 'Windows CE',
								'obigo'				=> 'Obigo',
								'netfront'			=> 'Netfront Browser',
								'openwave'			=> 'Openwave Browser',
								'mobilexplorer'		=> 'Mobile Explorer',
								'operamini'			=> 'Opera Mini',
								'opera mini'		=> 'Opera Mini',
								'digital paths'		=> 'Digital Paths',
								'avantgo'			=> 'AvantGo',
								'xiino'				=> 'Xiino',
								'novarra'			=> 'Novarra Transcoder',
								'vodafone'			=> 'Vodafone',
								'docomo'			=> 'NTT DoCoMo',
								'o2'				=> 'O2',
								'mobile'			=> 'Generic Mobile',
								'wireless'			=> 'Generic Mobile',
								'j2me'				=> 'Generic Mobile',
								'midp'				=> 'Generic Mobile',
								'cldc'				=> 'Generic Mobile',
								'up.link'			=> 'Generic Mobile',
								'up.browser'		=> 'Generic Mobile',
								'smartphone'		=> 'Generic Mobile',
								'cellphone'			=> 'Generic Mobile'
							),
							'robots' => array(
								'msnbot'			=> 'MSN Bot',
								'googlebot'			=> 'Google Bot',
								'mediapartners-google'	=> 'Google Adsense',
								'inktomi'			=> 'Yahoo Inktomi Bot',
								'slurp'				=> 'Yahoo Slurp Bot',
								'yahoo'				=> 'Yahoo',
								'askjeeves'			=> 'AskJeeves',
								'fastcrawler'		=> 'FastCrawler',
								'infoseek'			=> 'InfoSeek ; 1.0',
								'lycos'				=> 'Lycos',
								'baiduspider'		=> 'baiduspider bot',
								'job crawler'		=> 'baiduspiderbot',
								'analyzer'			=> 'job crawler bot',
								'arachnofilia'		=> 'arachnofilia bot',
								'aspseek'			=> 'aspseek bot',
								'bot'				=> 'bot',
								'check'				=> 'check bot',
								'crawl'				=> 'crawl bot',
								'infoseek'			=> 'infoseek bot',
								'netoskop'			=> 'netoskop bot',
								'netsprint'			=> 'NetSprint bot',
								'openfind'			=> 'openfind bot',
								'roamer'			=> 'roamer bot',
								'rover'				=> 'rover bot',
								'scooter'			=> 'scooter bot',
								'search'			=> 'search bot',
								'siphon'			=> 'siphon bot',
								'spider'			=> 'spider bot',
								'sweep'				=> 'sweep bot',
								'walker'			=> 'walker bot',
								'webstripper'		=> 'WebStripper bot',
								'wisenutbot'		=> 'wisenutbot bot',
								'gulliver'			=> 'gulliver bot',
								'validator'			=> 'validator bot',
								'yandex'			=> 'yandex bot',
								'ask jeeves'		=> 'ask jeeves bot',
								'moget@'			=> 'moget@ bot',
								'teomaagent'		=> 'teomaagent bot',
								'infoNavibot'		=> 'infoNavibot bot',
								'pphpdig'			=> 'PPhpDig bot',
								'gigabaz'			=> 'gigabaz bot',
								'webclipping.com'	=> 'Webclipping.com bot',
								'netmechanic'		=> 'netmechanic bot',
								'rrc'				=> 'RRC bot'
							)
						);

	/**
	* Contructor
	*/
	public function __construct() {
		$this->__user_agent = $_SERVER['HTTP_USER_AGENT'];
 
		foreach(array('_get_platform', '_get_browser', '_get_mobile', '_get_robot', '_get_ip') as $function) {
			$this->$function();
		}
	}
 
	/**
	* Obtiene la plataforma desde donde se accede
	*
	* @access      private
	* @return      void
	*/
	private function _get_platform() {
		foreach ($this->_useragents['platforms'] as $key => $val) {
			if (strpos(strtolower($this->__user_agent), $key) !== FALSE) {
				$this->platform = $val;
				break;
			}
		}
	}
 
	/**
	* Obtiene informacion del navegador (Nombre, Version)
	*
	* @access      private
	* @return      void
	*/
	private function _get_browser() {
		$test = strtolower($this->__user_agent);
 
		foreach ($this->_useragents['browsers'] as $key => $val) {
			if (preg_match(sprintf('/%s.*?([0-9\.]+)/i', preg_quote($key, '/')), $test, $match)) {
				$this->_isbrowser       = TRUE;
				$this->_isrobot	 = FALSE;
				$this->version	  = $match[1];
				$this->browser	  = $val;
				break;
			}
		}
 
		if (empty($this->browser)) $this->browser = preg_replace('/^([\w\s]+(?=\W)).*?$/', '\\1', $test);
 
		if (empty($this->version)) {
			$regex = sprintf('/%s.*?([0-9\.]+)/i', preg_quote($this->browser, '/'));
 
			if(preg_match($regex, $test, $match)) $this->version = $match[1];
		}
	}

	/**
	* Obtiene informacion del movil
	*
	* @access      private
	* @return      void
	*/
	private function _get_mobile() {
		foreach ($this->_useragents['mobiles'] as $key => $val)
		{
			if (strpos(strtolower($this->__user_agent), $key) !== FALSE)
			{
				$this->_ismobile = TRUE;
				$this->_isrobot = FALSE;
				$this->_isbrowser = FALSE;
				$this->mobile = $val;
				break;
			}
		}
	}
 
	/**
	 * Obtiene informacion sobre el robot
	 *
	 * @access      private
	 * @return      void
	 */
	private function _get_robot()
	{
		foreach ($this->_useragents['robots'] as $key => $val)
		{
			if (strpos(strtolower($this->__user_agent), $key) !== FALSE)
			{
				$this->_isbrowser = FALSE;
				$this->_ismobile = FALSE;
				$this->_isrobot = TRUE;
				$this->robot = $val;
				break;
			}
		}
	}

	private function _get_ip()
	{
		if ( ! empty($_SERVER['HTTP_CLIENT_IP']) )
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
    
		$this->user_IP = $ip;
	}
 
	/**
	 * ¿Es un Navegador de escritorio?
	 *
	 * @access      public
	 * @return      bool
	 */
	public function isBrowser()
	{
		return (bool) $this->_isbrowser;
	}
 
	/**
	 * ¿Es un Movil?
	 *
	 * @access      public
	 * @return      bool
	 */
	public function isMobile()
	{
		return (bool) $this->_ismobile;
	}
 
	/**
	 * ¿Es un robot?
	 *
	 * @access      public
	 * @return      bool
	 */
	public function isRobot()
	{
		return (bool) $this->_isrobot;
	}
}
?>
