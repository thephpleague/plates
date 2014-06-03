<?php

namespace League\Plates\Extension;

class Translate implements ExtensionInterface
{
    public $engine;
    public $template;
    
    /**
     * The translation string
     * @var array|\ArrayAccess Translation array container
     */
    protected $translation = null;
    
    /**
     * Current locale
     * @var string currently used locale. Usecase language switcher:selected
     */
    protected $current = null;
    
    /**
     * Available locales
     * @var array Array of available locales (['en_US', 'en_GB',...])
     */
    protected $locales = null;
    
    /**
     * Used to inject all data in the object
     * 
     * @method __construct
     * @access public
     * 
     * @param array|ArrayAccess $translation Array with translations
     * @param string $current The currently loaded locale (Optional)
     * @param array $locales Array with available locales (Optional) 
     */
    public function __construct($translation, $current = null, $locales = array())
    {
        $this->translation = $translation;
        $this->current = $current;
        $this->locales = $locales;
    }
    
    /**
     * Searches for key in the translation specified 
     * in the constructor and returns its value.
     * 
     * @method translate
     * @access public 
     * 
     * @param string $needle The needle that needs to be translated
     * @return string The value of the $needle or translated string 
     */
    public function translate($needle)
    {
        if (empty($this->translation)) {
            return $needle;
        }
        
        if (array_key_exists($needle, $this->translation)) {
            return $this->translation[$needle];
        } else {
            return $needle;
        }
    }
    
    
    public function getFunctions()
    {
        return array(
            'translate' => 'translate',
            'trans'     => 'translate',
            't'         => 'translate',
            '__'        => 'translate',
            'locales'   => 'availableLocales',
            'current'    => 'locale'
        );
    }
    
    /**
     * Exposes available locales to the template
     * 
     * Usecase: Language Switcher's flag generation
     * 
     * @method availableLocales
     * @access public
     * 
     * @return array An array of all locales passed
     */
    public function availableLocales()
    {
        return $this->locales;
    }
    
    /**
     * Getter method to retrieve the currently used locale.
     * Usecase: Language Switcher currently selected language.
     * 
     * @method locale
     * @access public
     * 
     * @return string The current locale in use.
     */
    public function locale()
    {
        return $this->current;
    }
}
