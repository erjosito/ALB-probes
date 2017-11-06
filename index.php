<html>
   <header>
     <title>Advanced probing for Azure Load Balancers</title>
   </header>
   <body>
     <h1>
       Welcome to the Lab on Advanced Healthchecks with Azure Load Balancer
     </h1>
     <br>
     <?php
         $reachabilityTest = True;
         $hosts = array ("bing.com", "google.com");
         $daemonTest = True;
         $daemons = array ("httpd", "sshd");
         $localTCPTest = True;
         $ports = array ("22", "80");
         // UDP ports not implemented because it requires root with nmap
     ?>
     <?php
        // Reachability test
        if ($reachabilityTest === True) {
            print ("<h2>Reachability Test</h2>\n");
            $allReachable = true;
            print ("        <ul>\n");
            foreach ($hosts as $host) {
                $result = exec ("ping -c 1 -W 1 " . $host . " 2>&1 | grep received");
                print ("            <li>" . $host . ": " . $result . "</li>\n");
                $pos = strpos ($result, "1 received");
                if ($pos === false) {
                    $allReachable = false;
                    break;
                }
            }
            print ("        </ul>\n");
            if ($allReachable === false) {
              // Ping did not work
              print ("        <p>At least one target host does not seem to be reachable (" . $host . ")</p>\n");
            } else {
              // Ping did work
              print ("        <p>All target hosts seem to be reachable</p>\n");
            }
        }
     ?>

     <?php
        // Daemon test
        if ($daemonTest === True) {
            print ("<h2>Daemon Test</h2>\n");
            $allRunning = True;
            print ("        <ul>\n");
            foreach ($daemons as $daemon) {
                $result = exec ("systemctl status " . $daemon . " | grep running");
                print ("            <li>" . $daemon . ": " . $result . "</li>\n");
                if (!(strlen ($result) > 0)) {
                    $allRunning = False;
                    break;
                }
            }
            print ("        </ul>\n");
            if ($allRunning === False) {
              // Daemon not running
              print ("        <p>At least one daemon does not seem to be running (" . $daemon . ")</p>\n");
            } else {
              // All daemons running
              print ("        <p>All daemons seem to be running</p>\n");
            }
        }
     ?>

     <?php
        // Local TCP Port Test
        if ($localTCPTest === True) {
            print ("<h2>Local TCP Port Test - nmap needs to be installed</h2>\n");
            // Check nmap is installed
            $nmapPath = exec ("which nmap 2>/dev/null");
            $allOpen = True;
            if ( strlen ($nmapPath) > 0 ) { 
                print ("        <ul>\n");
                foreach ($ports as $port) {
                    $result = exec ($nmapPath . " localhost -p " . $port . " | grep tcp");
                    print ("            <li>" . $result . "</li>\n");
                    $pos = strpos ($result, "open");
                    if ($pos === False) {
                        $allOpen = False;
                        break;
                    }
                  }
                print ("        </ul>\n");
                if ($allOpen === False) {
                    // Daemon not running
                    print ("        <p>At least one TCP port does not seem to be open (" . $port . ")</p>\n");
                } else {
                    // All daemons running
                    print ("        <p>All TCP ports seem to be open</p>\n");
                }
            } else {
              print ("<p>nmap not found in the system, nmap is required to test open ports</p>\n");
            }
        }
     ?>

     <?php
        // Return code evaluation
        if ( (!($reachabilityTest) || $allReachable) && (!($daemonTest) || $allRunning) && ( !($localTCPTest) || $allOpen) ) {
            http_response_code (200);
        } else {
            http_response_code (409);
        }
     ?>

   </body>
</html>
