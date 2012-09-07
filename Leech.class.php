<?php
/** 
 * Leech
 * 
 * Sucks out information from any given url as fetching from the browser.
 * 
 * @author		Jeyanth Kumar
 * @copyright	02-07-12
 */

/** 
 * Leech
 * 
 * Sucks out information from any given url as fetching from the browser.
 * <code>
 * $leech_obj = new Leech($url);
 * //or
 * $leech_obj = new Leech();
 * $leech_obj->seturl();
 * 
 * $post = array(
 * 	"train_no" => $tno, 
 * 	"date" => $date
 * );
 * 
 * $leech_obj->setpost($post);
 * $result = $leech_obj->leech();
 * </code>
 * 
 * @author		Jeyanth Kumar
 * @copyright	02-07-12
 */
class Leech {
	
	/**
	 * Url of the content to be leeched
	 * 
	 * @var String
	 */
	private $url;
	
	/**
	 * UserAgent of the request.
	 * 
	 * @var String
	 */
	private $ua;
	
	/**
	 * Post variables key as name and value as the value.
	 * 
	 * @var Array
	 */
	private $postfields;
	
	/**
	 * Additional header to be sent along with the request.
	 * 
	 * @var Array
	 */
	private $header;
	
	/**
	 * Referer header of the request. 
	 * 
	 * @var String
	 */
	private $referer;
	
	/**
	 * Request Timeout 
	 * 
	 * @var Integer
	 */
	private $timeout;
	
	/**
	 * Cookie String
	 * 
	 * @var String
	 */
	private $cookie_string;
	
	/**
	 * CookieJAR filepath
	 * 
	 * @var String
	 */
	private $cookie_file;
	
	/**
	 * Flag - random UA
	 * 
	 * @const 0
	 */
	const UA_RANDOM = 0;
	
	/**
	 * Flag - Mozilla Firefox UA
	 * 
	 * @const 1
	 */
	const UA_FIREFOX = 1;
	
	/**
	 * Flag - Google Chrome UA
	 * 
	 * @const 2
	 */
	const UA_CHROME = 2;
	
	/**
	 * Flag - Internet Explorer UA
	 * 
	 * @const 3
	 */
	const UA_IE = 3;
	
	/**
	 * Flag - Safari UA
	 * 
	 * @const 4
	 */
	const UA_SAFARI = 4;
	
	/**
	 * Flag - Opera UA
	 * 
	 * @const 5
	 */
	const UA_OPERA = 5;
	
	/**
	 * Flag - Rekonq UA
	 * 
	 * @const 6
	 */
	const UA_REKONQ = 6;
	
	/**
	 * User Agent String for Google Chrome
	 * 
	 * @var String
	 */
	public $uachrome = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5";
	
	/**
	 * User Agent String for Firefox
	 * 
	 * @var String
	 */
	public $uafirefox = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0";
	
	/**
	 * User Agent String for Rekonq
	 * 
	 * @var String
	 */
	public $uarekonq = "Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.34 (KHTML, like Gecko) rekonq Safari/534.342011-10-16 20:21:01";
	
	/**
	 * User Agent String for Safari
	 * 
	 * @var String
	 */
	public $uasafari = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; de-at) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1";
	
	/**
	 * User Agent String for Opera
	 * 
	 * @var String
	 */
	public $uaopera = "Opera/9.80 (Windows NT 6.0; U; pl) Presto/2.10.229 Version/11.62";
	
	/**
	 * User Agent String for Internet Explorer
	 * 
	 * @var String
	 */
	public $uaie = "IE 7 ? Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)2011-10-16 20:20:09";
	
	/**
	 * Constructor
	 * 
	 * Given an optional url to the leech class to leech the data. Just
	 * initializing the class.
	 * 
	 * <code>
	 * $leech_obj = new Leech("http://example.com/file.php");
	 * </code>
	 * 
	 * Class can be initiallized without setting the url.
	 * 
	 * <code>
	 * $leech_obj = new Leech();
	 * </code>
	 * 
	 * @param String $url OPTIONAL Url to be leeched
	 * @return void
	 */
	public function __construct($url = NULL) {
		$this->timeout = 0;
		$this->url = $url;
	}
	
	/**
	 * Setting URL
	 * 
	 * Use seturl to set the url to the leech class to leech the data. 
	 * <code>
	 * $leech_obj->set_url("http://www.example.com/page.php");
	 * </code>
	 * The URL to be leeched can be set or changed by using this method.
	 * This can be called as many time if multiple url's need to be leeched.
	 * <code>
	 * foreach($array_of_train_no as $tno) {
	 * 	$leech_obj->set_url("http://example.com/page.php?train_no=".$tno);
	 * 	$result[$tno] = $leech_obj->leech();
	 * }
	 * </code>
	 * 
	 * @param String $url Url to be leeched
	 * @return void
	 */
	public function set_url($url) {
		$this->url = $url;
	}
	
	/**
	 * Setting Referer
	 * 
	 * When visiting a webpage, the referrer or referring page is the 
	 * URL of the previous webpage from which a link was followed. 
	 * Use setreferer to set the referer url. 
	 * 
	 * <code>
	 * $leech_obj->set_url("http://example.com/page.php");
	 * $leech_obj->set_referer("http://example.com/anotherpage.php");
	 * </code>
	 * 
	 * The above snippet will leech page.php as the request following from 
	 * anotherpage.php
	 * 
	 * @param String $referer Referer url
	 * @return void
	 */
	public function set_referer($referer) {
		$this->referer = $referer;
	}
	
	/**
	 * Setting Additional Headers
	 * 
	 * Use setheader to set the additional headers to the leech class. like
	 * content length, type, age etc. in an array. 
	 * 
	 * <code>
	 * $header = array(
	 * 	'Accept: text/plain', 
	 * 	'Accept-Encoding: gzip,deflate,sdch',
	 * 	'Content-length: 100'
	 * );
	 * $leech_obj->set_header($header);
	 * $leech_obj->leech();
	 * </code>
	 * 
	 * The above snippet will send request with these specified headers 
	 * additionally. Note that the array item doesn't need array keys. Header
	 * name and the value will have to be a single string.
	 * 
	 * <code>
	 * [header_name]: [header_value]
	 * Accept-Encoding: gzip,deflate,sdch
	 * </code>
	 * 
	 * @param Array $header Additional Headers
	 * @return void
	 */
	public function set_header($header) {
		$this->header = $header;
	}
	
	/**
	 * Setting User Agents
	 * 
	 * Use setua to set the user agent of the request. Use class constants to
	 * set user agents built in the class or user agent string directly to set 
	 * the UA
	 * 
	 * <code>
	 * //request 1
	 * $ua = $leech_obj->set_ua(Leech::UA_SAFARI);
	 * $result[] = $leech_obj->leech();
	 * 
	 * //request 2
	 * $ua = $leech_obj->set_ua(Leech::UA_CHROME);
	 * $result[] = $leech_obj->leech();
	 * 
	 * //request 3
	 * $ua = $leech_obj->set_ua(Leech::UA_RANDOM);
	 * $result[] = $leech_obj->leech();
	 * </code>
	 * 
	 * In the above snippet. The first request is sent as sent from a safari
	 * web browser. The second request is sent as sent from a google chrome 
	 * browser. The third request uses a random user agent inside the class 
	 * to send the request.
	 * 
	 * The return value $ua is the useragent string that has been set.
	 * 
	 * Custom user agent can be used by
	 * <code>
	 * $leech_obj->set_ua("Custom UserAgent String (version 1.0)");
	 * $result = $leech_obj->leech();
	 * </code>
	 * 
	 * The above snippet will send the request with user agent "Custom UserAgent
	 * String (version 1.0)"
	 * 
	 * @param String|Integer $ua Class constants or UA String
	 * @return String User Agent assinged for the current request
	 */
	public function set_ua($ua) {
		if(is_int($ua))
			switch($ua) {
				case self::UA_RANDOM:
				$this->set_ua(rand(1,6));
				break;
				
				case self::UA_FIREFOX:
				$this->ua = $this->uafirefox;
				break;
				
				case self::UA_CHROME:
				$this->ua = $this->uachrome;
				break;
				
				case self::UA_IE:
				$this->ua = $this->uaie;
				break;
				
				case self::UA_SAFARI:
				$this->ua = $this->uasafari;
				break;
				
				case self::UA_OPERA:
				$this->ua = $this->uaopera;
				break;
				
				case self::UA_REKONQ:
				$this->ua = $this->uarekonq;
				break;
				
				default:
				$this->set_ua(rand(1,6));
			}
		else
			$this->ua = $ua;
		return $this->ua;
	}
	
	/**
	 * Setting Post Fields
	 * 
	 * Use setpost to set the request type to post and use the method paramenters
	 * to assign the value to the post fields and use the second paramenter
	 * to choose between the post type. UrlEncode or Formdata
	 * 
	 * Note Passing an array to set_post() will encode the data as 
	 * multipart/form-data, setting the optional parameter true will encode 
	 * the data as application/x-www-form-urlencoded.
	 * 
	 * To use the post as a normal post. Use the method directly. 
	 * <code>
	 * $post = array(
	 * 	"train_no" => $tno, 
	 * 	"date" => $date
	 * );
	 * $leech_obj->set_post($post);
	 * </code>
	 * 
	 * The above request will be sent with "Content-Type: multipart/form-data"
	 * Some script might not accept this type of form data which needs 
	 * application/x-www-form-urlencoded
	 * <code>
	 * $post = array(
	 * 	"train_no" => $tno, 
	 * 	"date" => $date
	 * );
	 * $leech_obj->set_post($post, TRUE);
	 * </code>
	 * 
	 * The form data for the above example will be "train_no=$tno&date=$date".
	 * 
	 * @param Array $post Post fields with key as name and value as value
	 * @param Bool $urlencode Optional Default is false. Set true to send the 
	 * request as urlencoded post.
	 * @return void
	 */
	public function set_post($post, $urlencode = false) {
		if($urlencode) {
			$fields_string = "";
			foreach($post as $key=>$value) {
				$fields_string .= urlencode($key).'='.urlencode($value).'&';
			}
			$this->postfields = rtrim($fields_string,'&');
		} else 
			$this->postfields = $post;
	}
	
	/**
	 * Timeout
	 * 
	 * The request will take server to process for some time. Use settimeout
	 * to set how long your script should wait for the response. 
	 * 
	 * <code>
	 * $leech_obj->set_timeout(2);
	 * </code>
	 * 
	 * The script will wait for only two seconds for the response.
	 * 
	 * @param Integer $timeout How long the script should wait in seconds.
	 * @return void
	 */
	public function set_timeout($timeout) {
		$this->timeout = $timeout;
	}
	
	/**
	 * Setting Cookie String
	 * 
	 * This will allow user to set the cookie as a string for any request.
	 * 
	 * <code>
	 * $leech_obj->set_cookie_string("uid=123;sid=345;");
	 * </code>
	 * 
	 * The above snipet will send request with two cookies uid and sid.
	 * 
	 * @param String $cookie List of cookies seperated by ;
	 * @return void
	 */
	public function set_cookie_string($cookie) {
		$this->cookie_string = $cookie;
	}
	
	/**
	 * Setting Cookie File
	 * 
	 * This will allow user to set the cookie from a file. The response can 
	 * also change the cookies present in file.
	 * 
	 * <code>
	 * $leech_obj->set_cookie_file("cookies.txt");
	 * </code>
	 * 
	 * @param String $cookie Path to the cookie file;
	 * @return void
	 */
	public function set_cookie_file($cookie) {
		$this->cookie_file = $cookie;
	}
	
	/**
	 * Leech
	 * 
	 * After setting up the URL throught constructor or method call leech to
	 * obtain data from the url. Other methods are optional. Will return 
	 * raw response data either html, xml, json, plaintext or etc unprocessed.
	 * 
	 * Simple use
	 * <code>
	 * $leech_obj = new Leech("http://example.com/page.php");
	 * $result = $leech_obj->leech();
	 * </code>
	 * 
	 * For configuring the request paramenters such as headers, user agent,
	 * post variables see other method.
	 * 
	 * @link #setpost Setting Post Variables
	 * @link #seturl Setting URL
	 * @link #setreferer Setting Referer
	 * @link #setua Setting Useragent
	 * @link #setheader Setting Additional Headers
	 * @link #setcookiestring Setting Cookies from string
	 * @link #setcookiefile Setting Cookies from a file.
	 * 
	 * @return String Response text as a string.
	 */
	public function leech() {
		if(is_null($this->ua))
			$this->set_ua(self::UA_RANDOM);
		
		$ch = curl_init($this->url);
        curl_setopt($ch,CURLOPT_USERAGENT,$this->ua);
        
        if(!is_null($this->postfields))
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postfields);
			
		if(!is_null($this->referer))
			curl_setopt($ch, CURLOPT_REFERER, $this->referer);
		
		if(!is_null($this->header))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
			
		if(!is_null($this->cookie_string))
			curl_setopt($ch, CURLOPT_COOKIE, $this->cookie_string);
			
		if(!is_null($this->cookie_file)) {
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);
		}
		
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $rawhtml = curl_exec($ch);
        curl_close($ch);
        
        return $rawhtml;
	}
}
