#!/usr/bin/php

<?php
/*
* Load json and convert it to PHP object:
*/
$rowstr = var_export(
    json_decode(
        file_get_contents(
            'https://raw.githubusercontent.com/opawg/user-agents/master/src/user-agents.json'
        ),
        true
    ),
    true
);

// autogenerate database
print <<<EOT
<?php
/* Autogenerated.  Do not edit */
namespace Opawg\UserAgents;
 
class UserAgents {
    public static function find(\$userAgent)
    {
        \$playerFound = null;
    
        //Search for current HTTP_USER_AGENT:
        foreach (self::\$db as \$player) {
            foreach (\$player['user_agents'] as \$userAgentsRegexp) {
                //Does the HTTP_USER_AGENT match this regexp:
                if (preg_match("#{\$userAgentsRegexp}#", \$userAgent)) {
                    \$playerFound = [
                        'app' => isset(\$player['app']) ? \$player['app'] : null,
                        'device' => isset(\$player['device'])
                            ? \$player['device']
                            : null,
                        'os' => isset(\$player['os']) ? \$player['os'] : null,
                        'bot' => isset(\$player['bot']) ? \$player['bot'] : 0,
                    ];
                    //We found it!
                    break 2;
                }
            }
        }
        return \$playerFound;
    }


EOT;
print "    static public \$db = ";
print $rowstr;
print ";\n";
print "}\n";