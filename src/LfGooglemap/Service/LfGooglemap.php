<?php

namespace LfGooglemap\Service;

/**
 * GMaps\Service\GoogleMap
 *
 * Zend Framework2 Google Map Class  (Google Maps API v3)
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * 
 * This class enables the creation of google maps
 *
 * @package                Zend Framework 2
 * @author                Ramkumar 
 */
 
class LfGoogleMap 
{

    //MAP TYPES
    public static $ROADMAP   = "ROADMAP";
    public static $SATELLITE = "SATELLITE";
    public static $HYBRID    = "HYBRID";
    public static $TERRAIN   = "TERRAIN";

    //ACCOUNT TYPES
    public static $FREE_ACCOUNT     = "free";
    public static $BUSINESS_ACCOUNT = "business";
    
    //GOOGLE MAP ACCOUNT TYPE
    //-----------------------
    var $account_Type = NULL;
    
    //FREE ACCOUNT PARAMETERS
    //------------------------
    var $client_id = "";
    var $cryptographic_key = "";
    
    //FREE ACCOUNT PARAMETERS
    //------------------------
    var $api_key = "";
    var $sensor = "false";
    var $libraries = array();
    var $apiUrl = "https://maps.googleapis.com/maps/api/js?v=3.exp";
    

    var $googlemapObjectName = 'map';
    var $markersCollectionObjectName = 'markers';
    var $mapType = NULL; // ( ROADMAP / SATELLITE / HYBRID / TERRAIN  )
    var $callbackFunction = '';
    var $div_id = '';
    var $div_class = '';
    var $zoom = 10;
    var $mapTypeControl = true;
    var $lat = -300;
    var $lon = 300;
    var $markers = array();
    var $height = "100px";
    var $width = "100px";
    var $animation = '';
    var $icon = '';
    var $icons = array();
    
    /**
     * Constructor
     */
    function __construct( $config ) 
    {
        $this->api_key              = $config["api-key"];
        $this->sensor               = $config["sensor"];   
        $this->libraries            = implode(",", $config["libraries"] );
        $this->apiUrl               = $config["api-url"];
       
        $this->client_id            = $config["client-id"];
        $this->cryptographic_key    = $config["cryptographic-key"];
   
        
        switch( $config["account-type"] )
        {
            case self::$FREE_ACCOUNT:
            case "free" :
            case "Free" :
            case "FREE" :
        	   $this->account_Type = self::$FREE_ACCOUNT;
        	   break;
        	
        	case self::$BUSINESS_ACCOUNT:
        	case "business":
        	case "Business":
        	case "BUSINESS":
        		$this->account_Type = self::$BUSINESS_ACCOUNT;
        		break;
        		
        		
        	default:
        		$this->account_Type = self::$FREE_ACCOUNT;
        		break;
        }
        
        switch( $config["map-type"] )
        {
        	case 'ROADMAP':
        	   $this->mapType      =  self::$ROADMAP;
        	    break;
        	    
    	    case 'SATELLITE':
    	       $this->mapType      =  self::$SATELLITE;
    	    	break;
    	    	
	    	case 'HYBRID':    
	    	    $this->mapType      =  self::$HYBRID;
	    		break;
	    		
    		case 'TERRAIN':
    		    $this->mapType      =  self::$TERRAIN;
    			break;
    			
    		default:
    		    $this->mapType      =  self::$ROADMAP;
    		    break;	    
        }    
    }
    
    
    public function getApiKey()
    {
    	return $this->api_key;
    }
    
    public function getAccountType()
    {
    	return $this->account_Type;
    }
    
    public function getMapType()
    {
    	return  $this->mapType;
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the user preferences
     *
     * Accepts an associative array as input, containing display preferences
     *
     * @access        public
     * @param        array        config preferences
     * @return        void
     */
    function initialize($config = array()) 
    {
        foreach ($config as $key => $val) 
        {
            if (isset($this->$key)) 
            {
                $this->$key = $val;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Generate the google map
     *
     * @access        public
     * @return        string
     */
    function generate() 
    {

        $out = '';

        $out .= '        <div id="' . $this->div_id . '" class="' . $this->div_class . '" style="height:' . $this->height . ';width:' . $this->width . ';"></div>';

        //-----------------------------------------------------------------
        //MANAGE API CALL DEPENDIN?G ON ACCOUNT TYPE ( free or business )
        //-----------------------------------------------------------------
        if( $this->account_Type == self::$FREE_ACCOUNT )
        {
            $out .= '        <script type="text/javascript" src="'.$this->apiUrl.'&key=' . $this->api_key . '&sensor=' . $this->sensor . '&libraries='.$this->libraries.'"></script>';
        }
        else
        {
            $out .= '        <script type="text/javascript" src="'.$this->apiUrl.'&client='.$this->client_id.'&signature='.$this->cryptographic_key.'&sensor=' . $this->sensor . '&libraries='.$this->libraries.'"></script>';
            
        }
        $out .= '        <script type="text/javascript">'; 
        $out .= 'var '.$this->markersCollectionObjectName.'= new Array();';
        
                               $out .= 'var '.$this->googlemapObjectName.' = null;
            
                                                function doAnimation() 
                                                {
                                                        if (marker.getAnimation() != null) 
                                                        {
                                                                marker.setAnimation(null); 
                                                        } 
                                                        else 
                                                        {
                                                                marker.setAnimation(google.maps.Animation.' . $this->animation . ');
                                                        }
                                                }
                
                                            function initialize() 
                                            {
                                                    
                                                    var myOptions = {
                                                            center: new google.maps.LatLng(' . $this->lat . ',' . $this->lon . '), 
                                                            Zoom:' . $this->zoom . ', 
                                                            mapTypeId: google.maps.MapTypeId.'.$this->mapType.',
                                                            mapTypeControl:'.$this->mapTypeControl.'
                                                            
                                                        };
                                                        ';


        $out .= '                        '.$this->googlemapObjectName.' = new google.maps.Map(document.getElementById("' . $this->div_id . '"), myOptions);';

        $i = 0;
        
     
        foreach ($this->markers as $key ) {

            if( array_key_exists("customInfo", $key) )
            {
                if( is_array( $key["customInfo"] ) )
                {
                	$out .= "var customData=".json_encode( $key["customInfo"]).";";
                }
                else
                {
                    $out .= "var customData='".$key["customInfo"]."';";
                }
            }
            else 
            {
                $out .= "var customData='';";
            }
            
            $out .="var marker" . $i . " = new google.maps.Marker({
                                                                                                                                                                         position: new google.maps.LatLng(". $key["latLng"]."), 
                                                                                                                                                                         map: ".$this->googlemapObjectName.",
                                                                                                                                                                         customInfo:customData,";
            if ($this->animation != '') {
                $out .="animation: google.maps.Animation." . $this->animation . ",";
            }
            if ($this->icon != '') {
                $out .="icon:'" . $this->icon . "',";
            } elseif (count($this->icons) > 0) {
                $out .="icon:'" . $this->icons[$i] . "',";
            }
            
            
            if( array_key_exists("title", $key) )
            {
                $out .="title:'" . $key['title'] . "'});";
            }
            else
            {
                $out .="title:''});";
            }
            
            if ($this->animation != '') {
                $out .="google.maps.event.addListener(marker" . $i . ", 'click', function(){".$key["onclick"]."( this )});";
            }
            
            $out .= $this->markersCollectionObjectName.".push(marker".$i.");";

            $i++;
        }

        $out .=  $this->callbackFunction.';              } 
                                                
                                                google.maps.event.addDomListener(window, "load", initialize);
                                        
                                        </script>';

        return $out;
    }
}