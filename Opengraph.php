<?php

namespace Facebook;

use DOMDocument;
use OutOfBoundsException;
use InvalidArgumentsException;

class Opengraph
{
    /**
     * Basic Metadata
     */
    const OG_TITLE              = 'og:title';
    const OG_TYPE               = 'og:type';
    const OG_IMAGE              = 'og:image';
    const OG_URL                = 'og:url';
    
    /**
     * Optional Metadata
     */
    const OG_IMAGE_SECURE_URL   = 'og:image:secure_url';
    const OG_IMAGE_TYPE         = 'og:image:type';
    const OG_IMAGE_WIDTH        = 'og:image:width';
    const OG_IMAGE_HEIGHT       = 'og:image:height';
    
    const OG_AUDIO              = 'og:audio';
    const OG_AUDIO_SECURE_URL   = 'og:audio:secure_url';
    const OG_AUDIO_TYPE         = 'og:audio:type';
    
    const OG_DESCRIPTION        = 'og:description';
    const OG_DETERMINER         = 'og:determiner';
    const OG_LOCALE             = 'og:locale';
    const OG_LOCALE_ALTERNATE   = 'og:locale:alternate';
    const OG_SITE_NAME          = 'og:site_name';
    
    const OG_VIDEO              = 'og:video';
    const OG_VIDEO_SECURE_URL   = 'og:video:secure_url';
    const OG_VIDEO_TYPE         = 'og:video:type';
    const OG_VIDEO_WIDTH        = 'og:video:width';
    const OG_VIDEO_HEIGHT       = 'og:video:height';
    
    /**
     * Facebook Metadata
     */
    const FB_ADMINS             = 'fb:admins';
    const FB_PAGE               = 'fb:page_id';
    const FB_APP                = 'fb:app_id';
    
    /**
     * DEPRECATED PROPERTIES!  DO NOT READ ON UNLESS YOU MUST!
     */
    
    const OG_LATITUDE           = 'og:latitude';
    const OG_LONGITUDE          = 'og:longitude';
    const OG_STREET_ADDRESS     = 'og:street-address';
    const OG_LOCALITY           = 'og:locality';
    const OG_REGION             = 'og:region';
    const OG_POSTAL_CODE        = 'og:postal-code';
    const OG_COUNTRY_NAME       = 'og:country-name';
    const OG_EMAIL              = 'og:email';
    const OG_PHONE_NUMBER       = 'og:phone_number';
    const OG_FAX_NUMBER         = 'og:fax_number';
    const OG_ISBN               = 'og:isbn';
    const OG_UPC                = 'og:upc';
    const OG_AUDIO_TITLE        = 'og:audio:title';
    const OG_AUDIO_ARTIST       = 'og:audio:artist';
    const OG_AUDIO_ALBUM        = 'og:audio:album';


    
    const APPEND                = 'append';
    const PREPEND               = 'prepend';
    
    /**
     * @var Array
     */
    public static $types = array(
        /**
         * Music
         */
        'music.song',
        'music.album',
        'music.playlist',
        'music.radio_station',
        
        /**
         * Video
         */
        'video.movie',
        'video.episode',
        'video.tv_show',
        'video.other',
        
        /**
         * No Vertical
         */
        'article',
        'book',
        'profile',
        'website',
        
        /**
         * DEPRECATED TYPE
         */
        'activity',
        'sport',
        'bar',
        'company',
        'cafe',
        'hotel',
        'restaurant',
        'cause',
        'sports_league',
        'sports_team',
        'band',
        'government',
        'non_profit',
        'school',
        'university',
        'actor',
        'athlete',
        'author',
        'director',
        'musician',
        'politician',
        'public_figure',
        'city',
        'country',
        'landmark',
        'state_province',
        'album',
        'drink',
        'food',
        'game',
        'movie',
        'product',
        'song',
        'tv_show',
        'blog'
    );
    
    /**
     * @var Array
     */
    protected static $meta = array();
    
    /**
     * @var Array
     */
    protected $_data = array();
    
    /**
     * Parse html tags
     * 
     * @param String $contents
     * @return Array
     */
    public function parse($contents)
    {
        $dom = new DOMDocument; 
        @$dom->loadHTML($contents); 
        $metas = $dom->getElementsByTagName('meta');
        foreach($metas as $i => $tag) { 
            if ($tag->hasAttribute('property')) {
                
                $key = str_replace(array('og:', 'fb:'), '', $tag->getAttribute('property'));
                $content = $tag->getAttribute('content');
                
                switch($tag->getAttribute('property')) {
                    case self::OG_TYPE:
                        if(!in_array($content, self::$types)) {
                            throw new OutOfBoundsException('Type "' . $content . '" invalid.');
                        }
                        $this->_data[$key] = $content;
                        break;
                        
                    case self::OG_TITLE:
                    case self::OG_URL:
                    case self::OG_DESCRIPTION:
                    case self::OG_SITE_NAME:
                    case self::OG_LATITUDE:
                    case self::OG_LONGITUDE:
                    case self::OG_STREET_ADDRESS:
                    case self::OG_LOCALITY:
                    case self::OG_REGION:
                    case self::OG_POSTAL_CODE:
                    case self::OG_COUNTRY_NAME:
                    case self::OG_EMAIL:
                    case self::OG_PHONE_NUMBER:
                    case self::OG_FAX_NUMBER:
                    case self::FB_PAGE:
                    case self::FB_APP:
                        $this->_data[$key] = $content;
                        break;
                    
                    case self::FB_ADMINS:
                    
                        $this->_data[$key] = (array)explode(',', $content);
                        break;
                    
                    case self::OG_IMAGE:
                        
                        if(!isset($this->_data[$key])) {
                            $this->_data[$key] = array();
                        }
                        
                        $this->_data[$key][] = $content;
                    
                        break;
                    
                    case self::OG_VIDEO:
                        
                        $video = array(
                            'src' => $content
                        );
                        
                        for($j = ($i+1); $j <= 3; $j++) {
                            $next = $metas->item($j);
                            $property = $next->getAttribute('property');
                            
                            switch($property) {
                                case self::OG_VIDEO_HEIGHT:
                                case self::OG_VIDEO_WIDTH:
                                case self::OG_VIDEO_TYPE:
                                    if(!isset($video[$property])) {
                                        $video[str_replace('og:video:', '', $property)] = $next->getAttribute('content');
                                    }
                                    break;
                            }
                        }

                        if(!isset($this->_data[$key])) {
                            $this->_data[$key] = array();
                        }
                        
                        $this->_data[$key][] = $video;
                        break;
                        
                    case self::OG_AUDIO:
                        
                        $audio = array(
                            'src' => $content
                        );
                        
                        for($j = ($i+1); $j <= 4; $j++) {
                            $next = $metas->item($j);
                            $property = $next->getAttribute('property');
                            
                            switch($property) {
                                case self::OG_AUDIO_TITLE:
                                case self::OG_AUDIO_ARTIST:
                                case self::OG_AUDIO_ALBUM:
                                case self::OG_VIDEO_TYPE:
                                    if(!isset($audio[$property])) {
                                        $audio[str_replace('og:audio:', '', $property)] = $next->getAttribute('content');
                                    }
                                    break;
                            }
                        }

                        if(!isset($this->_data[$key])) {
                            $this->_data[$key] = array();
                        }
                        
                        $this->_data[$key][] = $audio;
                        break;
                }
            } 
        }
        unset($dom);
        
        return $this->_data;
    }
    
    /**
     * Add meta
     * 
     * @param String $key
     * @param Mixed $value
     * @param String $position
     * @return \Facebook\Opengraph
     */
    public function addMeta($key, $value, $position)
    {
        if($key == self::OG_TYPE && !in_array($value, self::$types)) {
            throw new InvalidArgumentsException('Type "' . $value . '" invalid');
        }
        
        if($key == self::FB_ADMINS && is_string($value)) {
            $value = (array)explode(',', $value);
        }
        
        if($position == self::APPEND) {
            self::$meta[$key] = $value;
        } else {
            array_unshift(self::$meta, array($key => $value));
        }
        
        return $this;
    }
    
    /**
     * Append meta
     * 
     * @param String $key
     * @param String $value
     * @return \Facebook\Opengraph
     */
    public function append($key, $value)
    {
        return $this->addMeta($key, $value, self::APPEND);
    }

    /**
     * Prepend meta
     * 
     * @param String $key
     * @param String $value
     * @return \Facebook\Opengraph
     */
    public function prepend($key, $value)
    {
        return $this->addMeta($key, $value, self::PREPEND);
    }
    
    /**
     * Get meta value
     * 
     * @param String $key
     * @return Mixed
     */
    public function getMeta($key)
    {
        if(isset(self::$meta[$key])) {
            return self::$meta[$key];
        }
        
        return null;
    }
    
    /**
     * Get metas
     * 
     * @return Array
     */
    public function getMetas()
    {
        return self::$meta;
    }
    
    /**
     * Render meta tags
     * 
     * @return String
     */
    public function __toString()
    {
        $html = '';
        
        if(!empty(self::$meta)) {
            foreach(self::$meta as $key => $content) {
                $html .= "\t" . '<meta property="' . $key . '" content="' . $content . '" />' . PHP_EOL;
            }
        }
        return $html;
    }
}