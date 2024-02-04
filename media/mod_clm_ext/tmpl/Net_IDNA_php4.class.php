<?php
/**
* Adapter class for aligning the API of idna_convert with that of Net_IDNA
* @author  Matthias Sommerfeld <mso@phlylabs.de>
*/
class Net_IDNA_php4 extends idna_convert
{
    /**
     * Sets a new option value. Available options and values:
     * [encoding - Use either UTF-8, UCS4 as array or UCS4 as string as input ('utf8' for UTF-8,
     *         'ucs4_string' and 'ucs4_array' respectively for UCS4); The output is always UTF-8]
     * [overlong - Unicode does not allow unnecessarily long encodings of chars,
     *             to allow this, set this parameter to true, else to false;
     *             default is false.]
     * [strict - true: strict mode, good for registration purposes - Causes errors
     *           on failures; false: loose mode, ideal for "wildlife" applications
     *           by silently ignoring errors and returning the original input instead
     *
     * @param    mixed     Parameter to set (string: single parameter; array of Parameter => Value pairs)
     * @param    string    Value to use (if parameter 1 is a string)
     * @return   boolean   true on success, false otherwise
     * @access   public
     */
    function setParams($option, $param = false)
    {
        return $this->IC->set_parameters($option, $param);
    }
}
?>
