<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return
    array(
        "base_url"   => "http://localhost/hybridauth-git/hybridauth/",

        "providers"  => array(
            // openid providers
            "OpenID"    => array(
                "enabled" => true
            ),

            "Google"    => array(
                "enabled" => false,
                "keys"    => array("id" => "", "secret" => ""),
            ),

            "Facebook"  => array(
                "enabled" => false,
                "keys"    => array("id" => "", "secret" => ""),
            ),

            "Twitter"   => array(
                "enabled" => false,
                "keys"    => array("key" => "", "secret" => "")
            ),
            "Vkontakte" => array(
                "enabled" => true,
                "keys"    => array("id"=> "3180135", "secret"=> "yehtOURosLrFgtqKrraB")
            ),
        ),

        // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
        "debug_mode" => false,

        "debug_file" => "",
    );
